<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Orchestra\Parser\Xml\Facade as XmlParser;
use App\Models\Channel;
use App\Models\Newchannel;
use App\Models\EpgCategory;
use App\Models\Program;
use App\Models\Setting;
use App\Libraries\ImageHandler;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManagerStatic as Image;

class EpgController extends Controller {

    public function parse(Request $request)
    {
        if(isset($request->updated_by) && $request->updated_by == 'admin'){
            $role = 'admin';   
        } else {
            $role = 'cron';
        }

        $channels = Newchannel::where('active', 1)->get();
        foreach($channels as $c){
            $c->updated_by = $role;
            $c->save();
        }
        $settings = Setting::first();
        $xml = XmlParser::load($settings->xml_link);
        
        $cc = $xml->parse([
            'channel' => ['uses' => 'channel[::id>id,display-name,url,icon::src>src]'],
        ]);

        foreach($cc['channel'] as $c){
            $channel = Newchannel::where('channel_id', $c['id'])->first();
            if(!isset($channel->id) && isset($c['url'])){
                $channel = new Newchannel;
                $channel->channel_id = $c['id'];
                $channel->name = $c['display-name'];
                $channel->url = $c['url'];
                $channel->icon = $c['src'];
                $channel->updated_by = 'admin';
                $channel->save();
            }
        }
        

        $last = Program::orderBy('id', 'desc')->limit(1)->first();

        //$interval = date_diff(date_create(date('Y-m-d H:i:s')), $last->created_at);
        
        //if($interval->format('%h') > 2){
            $channels = $xml->parse([
                'programms' => ['uses' => 'programme[::start>start,::stop>stop,::channel>channel,title,desc]'],
            ]);

            foreach($channels['programms'] as $program){
                $c = Newchannel::where('channel_id', $program['channel'])->where('active', 1)->first();
                if(isset($c->channel_id)){
                    $p = new Program;

                    $data = explode(' ', $program['start']);
                    $datetime = date_create_from_format('YmdHis', $data[0]);
                    $zone = intval(substr($data[1], 0, -2));
                    date_sub($datetime, date_interval_create_from_date_string($zone.' hours'));
                    date_format($datetime, 'Y-m-d H:i:s');
                    $p->start = $datetime;

                    $data = explode(' ', $program['stop']);
                    $datetime = date_create_from_format('YmdHis', $data[0]);
                    $zone = intval(substr($data[1], 0, -2));
                    date_sub($datetime, date_interval_create_from_date_string($zone.' hours'));
                    date_format($datetime, 'Y-m-d H:i:s');
                    $p->stop = $datetime;

                    $p->channel_id = $c->id;
                    $p->title = $program['title'];
                    $p->subtitle = $program['desc'];
                    $p->save();
                }
            }

            Program::where('id', '<=', $last->id)->whereNotNull('start')->delete();
        //}       
        return redirect('/dashboard/epg');
    }

    public function checkLink()
    {
        $channels = Newchannel::where('active', 1)->get();
        foreach($channels as $channel){
            $handle = curl_init($channel->link);
            curl_setopt($handle,  CURLOPT_RETURNTRANSFER, TRUE);

            /* Get the HTML or whatever is linked in $url. */
            $response = curl_exec($handle);

            /* Check for 404 (file not found). */
            $httpCode = curl_getinfo($handle, CURLINFO_HTTP_CODE);
            if($httpCode != 200) {
                $channel->link_live = 0;
            } else {
                $channel->link_live = 1;
            }
            $channel->save();

            curl_close($handle);
        }        
    }

    public function index()
    {
        $epgcategories = EpgCategory::orderBy('order')->get();
        $active_channels = Newchannel::where('active', 1)->get();
        return view('epg.index', compact('epgcategories', 'active_channels'));
    }

    public function create()
    {
        $categories = EpgCategory::all();
        $channels = Newchannel::all();
        return view('epg.create', compact('channels', 'categories'));
    }

    public function show(Newchannel $channel)
    {
        $categories = EpgCategory::all();
        $channels = Newchannel::all();
        return view('epg.channel', compact('channel', 'categories', 'channels'));
    }

    public function store(Request $request)
    {
        if($request->channel == 0){
            $channel = new Newchannel;
            $channel->name = $request->channel1;
        } else {
            $channel = Newchannel::find($request->channel);
        }        
        $channel->link = $request->link;
        $channel->category_id = $request->category_id;
        $channel->link_live = 0;
        if(empty($request->is_premiered)){
            $channel->is_premiered = 0;
        } else {
            $channel->is_premiered = 1;
        }
        if(empty($request->visible)){
            $channel->visible = 0;
        } else {
            $channel->visible = 1;
        }
        $channel->active = 1;

        $month_year = date('FY').'/';
        $s3 = Storage::disk('s3');

        $filename = basename($_FILES['logo']['name']);            
        $small_filename = pathinfo($filename, PATHINFO_FILENAME) . '-small.' . pathinfo($filename, PATHINFO_EXTENSION);
        $img = Image::make($request->file('logo')->getRealPath())->resize(320, null, function ($constraint) {
            $constraint->aspectRatio();
        })->save(public_path('/public/images/' . $small_filename));
        chmod(public_path('/public/images/' . $small_filename), 0777);
        $s3->put($month_year.$small_filename, file_get_contents(env('APP_URL').'/public/images/'.$small_filename));

        $channel->icon = $month_year.$small_filename;


        $channel->save();
        return redirect('/dashboard/epg');
    }

    public function update(Newchannel $channel, Request $request)
    {
        $channel->active = 0;
        $channel->save();

        $c = Newchannel::find($request->channel);
        $c->link = $request->link;
        $c->category_id = $request->category_id;   
        if(empty($request->is_premiered)){
            $c->is_premiered = 0;
        } else {
            $c->is_premiered = 1;
        }
        if(empty($request->visible)){
            $c->visible = 0;
        } else {
            $c->visible = 1;
        }
        $c->active = 1;

        if(isset($request->logo)){
            $month_year = date('FY').'/';
            $upload_folder = '/images/'.$month_year;
            $s3 = Storage::disk('s3');

            $filename = basename($_FILES['logo']['name']);            
            $small_filename = pathinfo($filename, PATHINFO_FILENAME) . '-small.' . pathinfo($filename, PATHINFO_EXTENSION);
            $img = Image::make($request->file('logo')->getRealPath())->resize(320, null, function ($constraint) {
                $constraint->aspectRatio();
            })->save(public_path($upload_folder . $small_filename));
            $s3->put($month_year.$small_filename, file_get_contents(env('APP_URL').$upload_folder.$small_filename));

            $c->icon = $month_year.$small_filename;
        }
        $c->save();
        return redirect('/dashboard/epg');
    }

    public function destroy(Newchannel $channel)
    {
        $channel->active = 0;
        $channel->save();
        return redirect('/dashboard/epg');
    }

    public function programs(Newchannel $channel){
        return view('epg.program', compact('channel'));
    }

    public function storeprograms(Newchannel $channel, Request $request){
        $program = new Program;
        $program->channel_id = $channel->id;
        $program->title = $request->title;
        $program->subtitle = $request->subtitle;
        $program->save();
        return redirect('/dashboard/epg/channels/'.$channel->id);
    }
}