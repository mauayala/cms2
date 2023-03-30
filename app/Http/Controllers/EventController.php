<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Event;
use App\Models\Option;
use App\Models\Team;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManagerStatic as Image;

class EventController extends Controller {
    public function index(){
        Option::where('end_date', '<', date('Y-m-d H:i:s'))->delete();
        $events = json_decode(Event::orderBy('order', 'ASC')->get()->toJson());
        $events_raw = Event::orderBy('order', 'ASC')->get();
        $teams = Team::all();
        return view('events.index', compact('events', 'events_raw', 'teams'));
    }

    public function store(Request $request){
        $last_event = Event::orderBy('order', 'DESC')->first();

        if(isset($last_event->order)){
            $new_event_order = intval($last_event->order) + 1;
        } else {
            $new_event_order = 1;
        }
        $request->order = $new_event_order;
        $event = new Event;
        $event->order = $new_event_order;
        $event->name = $request->name;

        $month_year = date('FY').'/';
        $s3 = Storage::disk('s3');
        
        $filename = basename($_FILES['poster']['name']);
        $s3->put($month_year.$filename, file_get_contents($request->file('poster')->getRealPath()));
        $event->poster = $month_year.$filename;
        
        $small_filename = pathinfo($filename, PATHINFO_FILENAME) . '-small.' . pathinfo($filename, PATHINFO_EXTENSION);
        $img = Image::make($request->file('poster')->getRealPath())->resize(320, null, function ($constraint) {
            $constraint->aspectRatio();
        })->save(public_path('/public/images/' . $small_filename));
        chmod(public_path('/public/images/' . $small_filename), 0777);
        $s3->put($month_year.$small_filename, file_get_contents(env('APP_URL').'/public/images/'.$small_filename));
        
        unlink(public_path('/public/images/'.$small_filename));

        $event->save();
        if(isset($event->id)){
            return redirect('dashboard/events')->with(['note' => 'Events agregada con exito.', 'note_type' => 'success']);
        }
    }

    public function update(Event $event, Request $request){
        $event->name = $request->name;
        if($request->has('poster')) {
            $month_year = date('FY').'/';
            $s3 = Storage::disk('s3');
            
            $filename = basename($_FILES['poster']['name']);
            $s3->put($month_year.$filename, file_get_contents($request->file('poster')->getRealPath()));
            $event->poster = $month_year.$filename;
            
            $small_filename = pathinfo($filename, PATHINFO_FILENAME) . '-small.' . pathinfo($filename, PATHINFO_EXTENSION);
            $img = Image::make($request->file('poster')->getRealPath())->resize(320, null, function ($constraint) {
                $constraint->aspectRatio();
            })->save(public_path('/public/images/' . $small_filename));
            chmod(public_path('/public/images/' . $small_filename), 0777);
            $s3->put($month_year.$small_filename, file_get_contents(env('APP_URL').'/public/images/'.$small_filename));
            
            unlink(public_path('/public/images/' . $small_filename));
            unlink(public_path('/public/images/'.$filename));
        }
        $event->save();
        if(isset($event)){
            return redirect('dashboard/events')->with(['note' => 'Events actualiada con exito.', 'note_type' => 'success']);
        }
    }

    public function destroy(Event $event){
        $event->delete();
        return redirect('dashboard/events')->with(['note' => 'Events eliminada con exito.', 'note_type' => 'success']);
    }

    public function edit(Event $event){
        return view('events.edit', compact('event'));
    }

    public function order(Request $request){
        $category_order = json_decode($request->order);
        
        $events = Event::all();
        if(count($category_order) > count($events)){
            return 2;
        }
        $options = Option::all();
        $order = 1;
        
        foreach($category_order as $category_level_1) {
            $level1 = Event::find($category_level_1->id);
            if ($level1->id) {
                $level1->order = $order;
                $level1->save();
                $order += 1;
            } else {
                return 0;
            }

            if (isset($category_level_1->children)) {
                $children_level_1 = $category_level_1->children;
                foreach ($children_level_1 as $category_level_2) {
                    $level2 = Option::find($category_level_2->id);
                    if($level2->event_id != $level1->id){
                        return 0;
                    }
                    if ($level2->id) {
                        $level2->order = $order;
                        $level2->save();
                        $order += 1;
                    }
                }
            }
        }
        return 1;
    }
}
