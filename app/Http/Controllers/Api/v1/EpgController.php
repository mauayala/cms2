<?php
namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Orchestra\Parser\Xml\Facade as XmlParser;
use App\Models\Newchannel;
use App\Models\EpgCategory;
use App\Models\Program;
use Illuminate\Support\Facades\DB;

class EpgController extends Controller {

    public function parse()
    {
        $xml = XmlParser::load('http://epg.exabytetv.info/GuiaExabyteTV.xml');
        $channels = $xml->parse([
            'channels' => ['uses' => 'channel[::id>id,display-name>name,icon::src>logo,url>link]'],
            'programms' => ['uses' => 'programme[::start>start,::stop>stop,::channel>channel,title,sub-title]'],
        ]);

        foreach($channels['programms'] as $program){
            $c = new Program;
            $c->start = $channel['start'];
            $c->stop = $channel['stop'];
            $c->channel = $channel['channel'];
            $c->title = $channel['title'];
            $c->subtitle = $channel['sub-title'];
            $c->save();
        }
    }

    public function channels()
    {
        $channels = EpgCategory::join('newchannels', 'epg_categories.id', '=', 'newchannels.category_id')
            ->where('newchannels.active', 1)
            ->orderBy('order')
            ->get(['newchannels.id', 'newchannels.channel_id', 'newchannels.name', 
                DB::raw("CONCAT('https://s3.amazonaws.com/ctv3/', newchannels.icon) as logo"), 
                'newchannels.link', DB::raw('epg_categories.name as category_name')]);
        // $channels = Channel::where('active', 1)
        //     ->get(['id', 'channel_id', 'name', 
        //         DB::raw("CONCAT(env('APP_URL').'/channellogos/', logo) as logo"), 
        //         'link']);
        return response()->json($channels);
    }

    public function programs()
    {
        $programms = Program::select('start', 'stop', 'channel_id', 'title', 'subtitle')->distinct()->get();
        // for($i=0;$i<count($programms);$i++){
        //     $programms[$i]->channel = str_replace('Exabyte TV', 'Exabyte TV ', $programms[$i]->channel);
        // }
        return response()->json($programms);
    }

    public function programsNow()
    {
        $programms = Newchannel::where('active', 1)->whereHas('programs', function($q){
            $q->where('stop', '>=', date('Y-m-d H:i:s'))->where('start', '<=', date('Y-m-d H:i:s', time()+3*3600));
        })->with(['programs'=> function($q){
            $q->where('stop', '>=', date('Y-m-d H:i:s'))->where('start', '<=', date('Y-m-d H:i:s', time()+3*3600))->select('id', 'start', 'stop', 'channel_id', 'title', 'subtitle');
        }])->get(['id','channel_id', 'name', 'url', DB::raw("CONCAT('https://s3.amazonaws.com/ctv3/', icon) as logo"), 'link', 'link_live', 'is_premiered']);
        return response()->json($programms);
    }

    public function program(Request $request)
    {
        $channel = $request->channel;
        $programms = Program::select('start', 'stop', 'channel_id', 'title', 'subtitle')->where('channel_id', $channel)->distinct()->get();
        return response()->json($programms);
    }
    

    public function programsLimit(Request $request)
    {
        $programms = Program::where('start', '>=', date('Y-m-d H:i:s'))->take($request->limit)->get(['id','start', 'stop', 'channel_id', 'title', 'subtitle']);
        return response()->json($programms);
    }

    public function programLimit(Request $request)
    {
        $channel = $request->channel;
        $programms = Program::where('channel_id', $channel)->where('start', '>=', date('Y-m-d H:i:s'))->take($request->limit)->get(['id','start', 'stop', 'channel_id', 'title', 'subtitle']);
        return response()->json($programms);
    }

    public function featured()
    {
        $channels = Newchannel::where('active', 1)->where('is_premiered', 1)->get(['id', 'channel_id', 'name', DB::raw("CONCAT('https://s3.amazonaws.com/ctv3/', icon) as logo"), 'link']);
        return response()->json($channels);
    }
}