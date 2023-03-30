<?php

namespace App\Http\Controllers;

use Request;
use App\Models\Video;
use App\Models\Episode;
use App\Models\Favorite;
use App\Models\Menu;
use App\Models\VideoCategory;
use App\Models\User;
use App\Models\Setting;
use Illuminate\Support\Facades\Session;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function error404()
    {
        return view('home.error404');
    }

    public function videos(Request $request)
    {
        if(empty($request->page)){
            $page = 1;
        } else {
            $page = $request->page;
        }

        $videos = Video::where('active', 1)->orderBy('created_at', 'DESC')->simplePaginate(12);
        $page_title = 'All Videos';
        $page_description = 'Page ' . $page;
        $current_page = $page;
        $pagination_url = '/videos';
        return view('home.videos', compact('videos', 'page_title', 'page_description', 'current_page', 'pagination_url'));
    }

    public function video(Video $video)
    {
        $settings = Setting::first();
        //Make sure video is active
        if($video->active){
            $favorited = false;
            if(!\Auth::guest()):
                $favorited = Favorite::where('user_id', \Auth::user()->id)->where('video_id', $video->id)->first();
            endif;
            $view_increment = $this->handleViewCount($video->id);
            return view('home.video', compact('video', 'view_increment', 'favorited', 'settings'));
        } else {
            return redirect('videos')->with(['note' => 'Sorry, this video is no longer active.', 'note_type' => 'error']);
        }
    }

    public function episode(Episode $episode)
    {
        $settings = Setting::first();
        return view('home.episode', compact('episode', 'settings'));
    }

    public function handleViewCount($id){
        // check if this key already exists in the view_media session
        $blank_array = array();
        if (! array_key_exists($id, Session::get('viewed_video', $blank_array) ) ) {
            try{
                // increment view
                $video = Video::find($id);
                $video->views = $video->views + 1;
                $video->save();
                // Add key to the view_media session
                Session::put('viewed_video.'.$id, time());
                return true;
            } catch (Exception $e){
                return false;
            }
        } else {
            return false;
        }
    }

    public function user($username)
    {
        $user = User::where('username', $username)->first();
        
        $favorites = Favorite::where('user_id', $user->id)->orderBy('created_at', 'desc')->get();

        $favorite_array = array();
        foreach($favorites as $key => $fave){
            array_push($favorite_array, $fave->video_id);
        }

        $videos = Video::where('active', 1)->whereIn('id', $favorite_array)->take(9)->get();

        $type = 'profile';
        
        return view('home.user', compact('user', 'type', 'videos'));
    }
}
