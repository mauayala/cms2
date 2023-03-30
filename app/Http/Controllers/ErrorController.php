<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;
use Illuminate\Pagination\LengthAwarePaginator;
use DB;

use App\Models\Episode;
use App\Models\Error;
use App\Models\ErrorUser;
use App\Models\Message;
use App\Models\Serie;
use App\Models\User;
use App\Models\Video;

class ErrorController extends Controller {

    public function index(Request $request)
    {
        if(isset($request->search)){
            $errors = Error::whereHas('users', function($q) use($request) {
                $q->where('username', 'like', '%'.$request->search.'%');
            })->orWhereHas('video', function($q) use($request) {
                $q->where('title', 'like', '%'.$request->search.'%');
            })->orWhereHas('episode', function($q) use($request) {
                $q->where('title', 'like', '%'.$request->search.'%')
                    ->orWhereHas('serie', function($q) use($request) {
                        $q->where('title', 'like', '%'.$request->search.'%');
                    });
            })->withCount('users')->orderBy('users_count', 'desc')->orderBy('id')->paginate(10);
        } else {
            $errorsRaw = DB::table(DB::raw('errors, error_user'))
                ->select(DB::raw('errors.*'), DB::raw('count(error_user.id) as users_count'))
                ->whereColumn('error_user.error_id', 'errors.id')
                ->groupBy('error_user.error_id')
                ->orderBy('users_count', 'desc')
                ->orderBy('errors.id')
                ->get();

            $page = LengthAwarePaginator::resolveCurrentPage();

            $perPage = 10;
            $currentPageItems = $errorsRaw->slice(($page*$perPage) - $perPage, $perPage)->all();

            $errors = new LengthAwarePaginator(
                $currentPageItems, 
                count($errorsRaw), 
                $perPage
            );
            $errors->setPath($request->url());
            //$errors = Error::select(DB::raw('*'), DB::raw('count(error_user.id) as users_count'))->where('error_user.error_id', 'errors.id')->groupBy('error_user.error_id')->orderBy('users_count', 'desc')->orderBy('errors.id')->simplePaginate(10);
        }
        return view('errors.index', compact('errors'));
    }

    public function error(Error $error)
    {
        $users = $error->users()->groupBy('username')->get(['firstname', 'lastname', 'username', DB::raw('count(username) as cnt')]);
        return response()->json($users);
    }

    public function delete(Error $error)
    {
        $errorUser = ErrorUser::where('error_id', $error->id)->delete();
        $error->delete();
    }

    public function resolve(Error $error)
    {
        $errorUsers = ErrorUser::where('error_id', $error->id)->get();
        foreach ($errorUsers as $errorUser) {
            $message = new Message;
            if(is_null($error->video_id)) {
                $message->content = 'Hola, el episodio '.$error->episode->title.' de '.$error->episode->serie->title.' ha sido arreglado.';
            } else {
                $message->content = 'Hola, '.$error->video->title.' ha sido arreglada.';
            }            
            $message->user_id = $errorUser->user_id;
            $message->save();
            $errorUser->delete();
        }
        $error->delete();
    }

    public function checkLinkMovie(Video $video, Request $request)
    {
        $file_headers = get_headers($video->stream);
        if($file_headers[0] == 'HTTP/1.1 404 Not Found') {
            $video->link_works = 0;
            return redirect('/dashboard/errors/movies?videos='.($request->has('videos') ? $request->videos : 1))->with(['note' => 'Link(s) does not fixed!', 'note_type' => 'error']);
        } else {
            $video->link_works = 1;
        }
        $video->save();

        return back()->with(['note' => 'Link(s) are fixed!', 'note_type' => 'success']);
    }

    public function checkLinkSubtitleMovie(Video $video, Request $request)
    {
        if($video->full_subtitle_file_name == '' || $video->full_subtitle_file_name == null) {
            $video->link_subtitle_works = 0;
            return redirect('/dashboard/errors/movies?subtitles='.($request->has('subtitles') ? $request->subtitles : 1))->with(['note' => 'Link(s) does not fixed!', 'note_type' => 'error']);
        } else {
            $file_headers = get_headers($video->full_subtitle_file_name);
            if($file_headers[0] == 'HTTP/1.1 404 Not Found') {
                $video->link_subtitle_works = 0;
                return redirect('/dashboard/errors/movies?subtitles='.($request->has('subtitles') ? $request->subtitles : 1))->with(['note' => 'Link(s) does not fixed!', 'note_type' => 'error']);
            } else {
                $video->link_subtitle_works = 1;
            }
        }
        $video->save();

        return redirect('/dashboard/errors/movies?subtitles='.($request->has('subtitles') ? $request->subtitles : 1))->with(['note' => 'Link(s) are fixed!', 'note_type' => 'success']);
    }

    public function checkLinkSubtitleEsMovie(Video $video, Request $request)
    {
        if($video->full_subtitle_file_name_es == '' || $video->full_subtitle_file_name_es == null) {
            $video->link_subtitle_es_works = 0;
            return redirect('/dashboard/errors/movies?subtitles_es='.($request->has('subtitles_es') ? $request->subtitles_es : 1))->with(['note' => 'Link(s) does not fixed!', 'note_type' => 'error']);
        } else {
            $file_headers = get_headers($video->full_subtitle_file_name_es);
            if($file_headers[0] == 'HTTP/1.1 404 Not Found') {
                $video->link_subtitle_es_works = 0;
                return redirect('/dashboard/errors/movies?subtitles_es='.($request->has('subtitles_es') ? $request->subtitles_es : 1))->with(['note' => 'Link(s) does not fixed!', 'note_type' => 'error']);
            } else {
                $video->link_subtitle_es_works = 1;
            }
        }
        $video->save();

        return redirect('/dashboard/errors/movies?subtitles_es='.($request->has('subtitles_es') ? $request->subtitles_es : 1))->with(['note' => 'Link(s) are fixed!', 'note_type' => 'success']);
    }

    public function archiveLinkMovie(Video $video, Request $request)
    {
        $video->link_works = 2;
        $video->save();

        return redirect('/dashboard/errors/movies?videos='.($request->has('videos') ? $request->videos : 1))->with(['note' => 'Archived!', 'note_type' => 'success']);
    }

    public function archiveLinkSubtitleMovie(Video $video, Request $request)
    {
        $video->link_subtitle_works = 2;
        $video->save();

        return redirect('/dashboard/errors/movies?subtitles='.($request->has('subtitles') ? $request->subtitles : 1))->with(['note' => 'Archived!', 'note_type' => 'success']);
    }

    public function archiveLinkSubtitleEsMovie(Video $video, Request $request)
    {
        $video->link_subtitle_es_works = 2;
        $video->save();

        return redirect('/dashboard/errors/movies?subtitles_es='.($request->has('subtitles_es') ? $request->subtitles_es : 1))->with(['note' => 'Archived!', 'note_type' => 'success']);
    }

    public function unarchiveLinkMovie(Video $video, Request $request)
    {
        $video->link_works = 0;
        $video->save();

        return redirect('/dashboard/errors/movies?archived_videos='.($request->has('archived_videos') ? $request->archived_videos : 1))->with(['note' => 'Unarchived!', 'note_type' => 'success']);
    }

    public function unarchiveLinkSubtitleMovie(Video $video, Request $request)
    {
        $video->link_subtitle_works = 0;
        $video->save();

        return redirect('/dashboard/errors/movies?archived_subtitles='.($request->has('archived_subtitles') ? $request->archived_subtitles : 1))->with(['note' => 'Unarchived!', 'note_type' => 'success']);
    }

    public function unarchiveLinkSubtitleEsMovie(Video $video, Request $request)
    {
        $video->link_subtitle_es_works = 0;
        $video->save();

        return redirect('/dashboard/errors/movies?archived_subtitles_es='.($request->has('archived_subtitles_es') ? $request->archived_subtitles_es : 1))->with(['note' => 'Unarchived!', 'note_type' => 'success']);
    }

    public function showNotWorkingLinkVideos()
    {
        $videos = Video::where('link_works', 0)->paginate(100, ['*'], 'videos');
        $last_checked = Video::where('last_checked', 1)->first();
        $checked = Video::where('id', '<', $last_checked->id)->count();

        $subtitle_videos = Video::withCount('video_views')->where('link_subtitle_works', 0)->orderBy('video_views_count', 'desc')->paginate(100, ['*'], 'subtitles');
        $last_subtitle_checked = Video::where('last_subtitle_checked', 1)->first();
        $subtitle_checked = Video::where('id', '<', $last_subtitle_checked->id)->count();

        $subtitle_es_videos = Video::withCount('video_views')->where('link_subtitle_es_works', 0)->orderBy('video_views_count', 'desc')->paginate(100, ['*'], 'subtitles_es');
        $last_subtitle_es_checked = Video::where('last_subtitle_es_checked', 1)->first();
        $subtitle_es_checked = Video::where('id', '<', $last_subtitle_es_checked->id)->count();

        $archived_videos = Video::where('link_works', 2)->paginate(100, ['*'], 'archived_videos');
        $archived_subtitles = Video::where('link_subtitle_works', 2)->paginate(100, ['*'], 'archived_subtitles');
        $archived_subtitles_es = Video::where('link_subtitle_es_works', 2)->paginate(100, ['*'], 'archived_subtitles_es');

        $total = Video::count();

        return view('errors.not_working_link_videos', compact('videos', 'checked', 'subtitle_videos', 'subtitle_checked', 'subtitle_es_videos', 'subtitle_es_checked', 'total', 'archived_videos', 'archived_subtitles', 'archived_subtitles_es'));
    }

    public function checkLinkEpisode(Episode $episode)
    {
        $file_headers = get_headers($episode->stream1);
        if($file_headers[0] == 'HTTP/1.1 404 Not Found') {
            $episode->link_works = 0;
            return redirect('/dashboard/errors/episodes?episodes='.($request->has('episodes') ? $request->episodes : 1))->with(['note' => 'Link(s) does not fixed!', 'note_type' => 'error']);
        } else {
            $episode->link_works = 1;
            $episode->checked_by = \Auth::user()->id;
        }
        $episode->save();

        return redirect('/dashboard/errors/episodes?episodes='.($request->has('episodes') ? $request->episodes : 1))->with(['note' => 'Link(s) are fixed!', 'note_type' => 'success']);
    }

    public function checkLinkSubtitleEpisode(Episode $episode)
    {
        if($episode->full_subtitle_file_name == '' || $episode->full_subtitle_file_name == null) {
            return redirect('/dashboard/errors/episodes?subtitles='.($request->has('subtitles') ? $request->subtitles : 1))->with(['note' => 'Link(s) does not fixed!', 'note_type' => 'error']);
        } else {
            $file_headers = get_headers($episode->full_subtitle_file_name);
            if($file_headers[0] == 'HTTP/1.1 404 Not Found') {
                $episode->link_subtitle_works = 0;
                return redirect('/dashboard/errors/episodes?subtitles='.($request->has('subtitles') ? $request->subtitles : 1))->with(['note' => 'Link(s) does not fixed!', 'note_type' => 'error']);
            } else {
                $episode->link_subtitle_works = 1;
                $episode->checked_by = \Auth::user()->id;
            }
        }
        
        $episode->save();

        return redirect('/dashboard/errors/episodes?subtitles='.($request->has('subtitles') ? $request->subtitles : 1))->with(['note' => 'Link(s) are fixed!', 'note_type' => 'success']);
    }

    public function checkLinkSubtitleEsEpisode(Episode $episode)
    {
        if($episode->full_subtitle_file_name_es == '' || $episode->full_subtitle_file_name_es == null) {
            $episode->link_subtitle_es_works = 0;
            return redirect('/dashboard/errors/episodes?subtitles_es='.($request->has('subtitles_es') ? $request->subtitles_es : 1))->with(['note' => 'Link(s) does not fixed!', 'note_type' => 'error']);
        } else {
            $file_headers = get_headers($episode->full_subtitle_file_name_es);
            if($file_headers[0] == 'HTTP/1.1 404 Not Found') {
                $episode->link_subtitle_es_works = 0;
                return redirect('/dashboard/errors/episodes?subtitles_es='.($request->has('subtitles_es') ? $request->subtitles_es : 1))->with(['note' => 'Link(s) does not fixed!', 'note_type' => 'error']);
            } else {
                $episode->link_subtitle_es_works = 1;
                $episode->checked_by = \Auth::user()->id;
            }
        }
        
        $episode->save();

        return redirect('/dashboard/errors/episodes?subtitles_es='.($request->has('subtitles_es') ? $request->subtitles_es : 1))->with(['note' => 'Link(s) are fixed!', 'note_type' => 'success']);
    }

    public function archiveLinkEpisode(Episode $episode, Request $request)
    {
        $episode->link_works = 2;
        $episode->save();

        return redirect('/dashboard/errors/episodes?episodes='.($request->has('episodes') ? $request->episodes : 1))->with(['note' => 'Archived!', 'note_type' => 'success']);
    }

    public function archiveLinkSubtitleEpisode(Episode $episode, Request $request)
    {
        $episode->link_subtitle_works = 2;
        $episode->save();

        return redirect('/dashboard/errors/episodes?subtitles='.($request->has('subtitles') ? $request->subtitles : 1))->with(['note' => 'Archived!', 'note_type' => 'success']);
    }

    public function archiveLinkSubtitleEsEpisode(Episode $episode, Request $request)
    {
        $episode->link_subtitle_es_works = 2;
        $episode->save();

        return redirect('/dashboard/errors/episodes?subtitles_es='.($request->has('subtitles_es') ? $request->subtitles_es : 1))->with(['note' => 'Archived!', 'note_type' => 'success']);
    }

    public function unarchiveLinkEpisode(Episode $episode, Request $request)
    {
        $episode->link_works = 0;
        $episode->save();

        return redirect('/dashboard/errors/episodes?archived_videos='.($request->has('archived_videos') ? $request->archived_videos : 1))->with(['note' => 'Unarchived!', 'note_type' => 'success']);
    }

    public function unarchiveLinkSubtitleEpisode(Episode $episode, Request $request)
    {
        $episode->link_subtitle_works = 0;
        $episode->save();

        return redirect('/dashboard/errors/episodes?archived_subtitles='.($request->has('archived_subtitles') ? $request->archived_subtitles : 1))->with(['note' => 'Unarchived!', 'note_type' => 'success']);
    }

    public function unarchiveLinkSubtitleEsEpisode(Episode $episode, Request $request)
    {
        $episode->link_subtitle_es_works = 0;
        $episode->save();

        return redirect('/dashboard/errors/episodes?archived_subtitles_es='.($request->has('archived_subtitles_es') ? $request->archived_subtitles_es : 1))->with(['note' => 'Unarchived!', 'note_type' => 'success']);
    }

    public function showNotWorkingLinkEpisodes(Request $request)
    {
        $series = Serie::orderBy('title')->get();

        $episodes = Episode::select('*', 'episodes.id as id')->with('serie')->where('link_works', 0)->join('series', 'series.id', '=', 'episodes.serie_id')->orderBy('series.title')->paginate(100, ['*'], 'episodes');
        $last_checked = Episode::where('last_checked', 1)->first();
        $checked = Episode::where('id', '<', $last_checked->id)->count();

        $subtitle_episodes = Episode::with('serie')->where('link_subtitle_works', 0)->paginate(100, ['*'], 'subtitles');
        $last_subtitle_checked = Episode::where('last_subtitle_checked', 1)->first();
        $subtitle_checked = Episode::where('id', '<', $last_subtitle_checked->id)
                                ->whereHas('serie', function($q){
                                    $q->where('script_check', 1);
                                })->count();

        $subtitle_es_episodes = Episode::with('serie')->where('link_subtitle_es_works', 0)->paginate(100, ['*'], 'subtitles_es');
        $last_subtitle_es_checked = Episode::where('last_subtitle_es_checked', 1)->first();
        $subtitle_es_checked = Episode::where('id', '<', $last_subtitle_es_checked->id)
                                    ->whereHas('serie', function($q){
                                        $q->where('script_check', 1);
                                    })
                                    ->count();

        $total = Episode::count();
        $total_subtitle = Episode::whereHas('serie', function($q){
            $q->where('script_check', 1);
        })->count();

        $users = User::whereIn('id', function($q){
            $q->select('checked_by')->from('episodes')->whereNotNull('checked_by')->groupBy('checked_by');
        })->get();

        $archived_episodes = Episode::where('link_works', 2)->paginate(100, ['*'], 'archived_episodes');
        $archived_subtitles = Episode::where('link_subtitle_works', 2)->paginate(100, ['*'], 'archived_subtitles');
        $archived_subtitles_es = Episode::where('link_subtitle_es_works', 2)->paginate(100, ['*'], 'archived_subtitles_es');

        return view('errors.not_working_link_episodes', compact('series', 'episodes', 'total', 'total_subtitle', 'checked', 'users', 'subtitle_episodes', 'subtitle_checked', 'subtitle_es_episodes', 'subtitle_es_checked', 'archived_episodes', 'archived_subtitles', 'archived_subtitles_es'));
    }
}