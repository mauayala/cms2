<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Event;
use App\Models\Option;
use App\Models\Team;

class OptionController extends Controller {
    public function store(Request $request){
        $last_option = Option::orderBy('order', 'DESC')->first();

        if(isset($last_option->order)){
            $new_option_order = intval($last_option->order) + 1;
        } else {
            $new_option_order = 1;
        }
        $option = new Option;
        $option->order = $new_option_order;
        $option->event_id = $request->event_id;
        $option->name = $request->name;
        $option->mbps = $request->mbps;
        $option->link = $request->link;
        $option->start_date = date('Y-m-d H:i:s', strtotime($request->start_date));
        $option->end_date = date('Y-m-d H:i:s', strtotime($request->end_date));
        $option->save();
        if(isset($option->id)){
            return redirect('dashboard/events')->with(['note' => 'Options agregada con exito.', 'note_type' => 'success']);
        }
    }

    public function update(Option $option, Request $request){
        $option->event_id = $request->event_id;
        $option->name = $request->name;
        $option->mbps = $request->mbps;
        $option->link = $request->link;
        $option->start_date = date('Y-m-d H:i:s', strtotime($request->start_date));
        $option->end_date = date('Y-m-d H:i:s', strtotime($request->end_date));
        $option->save();

        $option->teams()->detach();
        $option->teams()->attach($request->team_id1);
        $option->teams()->attach($request->team_id2);
        return redirect('dashboard/events')->with(['note' => 'Options actualiada con exito.', 'note_type' => 'success']);
    }

    public function destroy(Option $option){
        $option->delete();
        return redirect('dashboard/events')->with(['note' => 'Options eliminada con exito.', 'note_type' => 'success']);
    }

    public function edit(Option $option){
        $events = Event::all();
        $teams = Team::all();
        return view('events.options.edit', compact('option', 'events', 'teams'));
    }
}
