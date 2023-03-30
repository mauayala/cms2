<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\Team;

class TeamController extends Controller {

    public function index(){
        $teams = Team::all();
        return view('events.teams.index', compact('teams'));
    }

    public function store(Request $request){
        $team = new Team;
        $team->name = $request->name;

        if($request->has('logo')) {
            $s3 = Storage::disk('s3');
            
            $filename = basename($_FILES['logo']['name']);
            $s3->put('teams/'.$filename, file_get_contents($request->file('logo')->getRealPath()));
            $team->logo = 'teams/'.$filename;
        }

        $team->save();
        if(isset($team->id)){
            return redirect('dashboard/events')->with(['note' => 'Teams agregada con exito.', 'note_type' => 'success']);
        }
    }

    public function destroy(Team $team){
        $team->delete();
        return redirect('dashboard/events/teams')->with(['note' => 'Teams eliminada con exito.', 'note_type' => 'success']);
    }

    public function edit(Team $team){
        return view('events.teams.edit', compact('team'));
    }

    public function update(Team $team, Request $request){
        $team->name = $request->name;

        if($request->has('logo')) {
            $s3 = Storage::disk('s3');
            
            $filename = basename($_FILES['logo']['name']);
            $s3->put('teams/'.$filename, file_get_contents($request->file('logo')->getRealPath()));
            $team->logo = 'teams/'.$filename;
        }

        $team->save();
        return redirect('dashboard/events/teams')->with(['note' => 'Teams agregada con exito.', 'note_type' => 'success']);
    }
}
