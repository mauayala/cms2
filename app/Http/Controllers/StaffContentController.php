<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Episode;
use App\Models\Season;
use App\Models\Serie;
use App\Models\SerieCategory;
use App\Models\User;
use App\Models\Video;

class StaffContentController extends Controller 
{   
    public function create(Request $request)
    {
        $users = User::where('role', 'staff')->where('active', 1)->get();
        if($request->has('status')) {
            $series = Serie::where('status', 'Ended');    
        } else {
            $series = Serie::whereIn('status', ['Airing', 'Paused']);
        }
        if($request->has('letter') && $request->letter == 'numbers') {
            $series = $series->where('title', 'like', '0%')
                                ->orWhere('title', 'like', '1%')
                                ->orWhere('title', 'like', '2%')
                                ->orWhere('title', 'like', '3%')
                                ->orWhere('title', 'like', '4%')
                                ->orWhere('title', 'like', '5%')
                                ->orWhere('title', 'like', '6%')
                                ->orWhere('title', 'like', '7%')
                                ->orWhere('title', 'like', '8%')
                                ->orWhere('title', 'like', '9%');
        } elseif($request->has('letter')) {
            $series = $series->where('title', 'like', $request->letter.'%');
        } else {
            $series = $series->take(20);
        }

        $series = $series->orderBy('title')->get();
        
        return view('staff-content.create_edit', compact('users', 'series'));
    }

    public function store(Request $request)
    {
        foreach($request->serie_id as $serie_id) {
            $serie = Serie::find($serie_id);

            $serie->update([
                'assigned_to'           => $request->user_ids[$serie_id],
                'season_number'         => (!isset($request->season_numbers[$serie_id])) ? null : $request->season_numbers[$serie_id],
                'episode_number'        => (!isset($request->episode_numbers[$serie_id])) ? null : $request->episode_numbers[$serie_id],
                'monday'                => (!isset($request->mondays[$serie_id])) ? 0 : 1,
                'tuesday'               => (!isset($request->tuesdays[$serie_id])) ? 0 : 1,
                'wednesday'             => (!isset($request->wednesdays[$serie_id])) ? 0 : 1,
                'thursday'              => (!isset($request->thursdays[$serie_id])) ? 0 : 1,
                'friday'                => (!isset($request->fridays[$serie_id])) ? 0 : 1,
                'saturday'              => (!isset($request->saturdays[$serie_id])) ? 0 : 1,
                'sunday'                => (!isset($request->sundays[$serie_id])) ? 0 : 1,
                'status'                => $request->statuses[$serie_id],
                'link_subtitle_works'   => (!isset($request->link_subtitle_works[$serie_id])) ? 0 : 1,
                'link_subtitle_es_works'=> (!isset($request->link_subtitle_es_works[$serie_id])) ? 0 : 1
            ]);
        }

        return \Redirect::to('dashboard/staff-content')->with(['note' => 'Series has been successfully added!', 'note_type' => 'success']);
    }

    public function getSeasons(Request $request)
    {
        $seasons = Season::where('serie_id', $request->serie_id)->get();
        return response()->json($seasons);
    }

    public function seasonsStore(Request $request)
    {
        $episodes = Episode::where('season_id', $request->season_id)->get();

        $serie = Serie::find($request->serie_id);
        $serie->assigned_to = $request->user_id;
        $serie->status = 'Airing';
        $serie->link_subtitle_works = $request->has('link_subtitle_works') ? 1 : 0;
        $serie->link_subtitle_es_works = $request->has('link_subtitle_es_works') ? 1 : 0;
        $serie->save();

        foreach($episodes as $episode) {
            $episode->update([
                'air_date' => date('Y-m-d H:i:s', strtotime($request->due_date))
            ]);

            if($episode->full_subtitle_file_name == '' || $episode->full_subtitle_file_name == null) {
                $episode->link_subtitle_works = 0;   
            } else {
                $file_headers = get_headers($episode->full_subtitle_file_name);
                if($file_headers[0] == 'HTTP/1.1 404 Not Found') {
                    $episode->link_subtitle_works = 0;
                } else {
                    $episode->link_subtitle_works = 1;
                }
            }
            
            $episode->save();

            if($episode->full_subtitle_file_name_es == '' || $episode->full_subtitle_file_name_es == null) {
                $episode->link_subtitle_es_works = 0;   
            } else {
                $file_headers = get_headers($episode->full_subtitle_file_name_es);
                if($file_headers[0] == 'HTTP/1.1 404 Not Found') {
                    $episode->link_subtitle_es_works = 0;
                } else {
                    $episode->link_subtitle_es_works = 1;
                }
            }
            
            $episode->save();
        }

        return back()->withSuccess('Successfully assigned');
    }
}