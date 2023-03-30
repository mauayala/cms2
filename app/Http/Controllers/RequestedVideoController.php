<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RequestedVideo;

class RequestedVideoController extends Controller 
{
    public function index()
    {
        $videos = RequestedVideo::where('status', 0)->groupBy('title')->orderBy('id','desc')->get();

        return view('requested_videos.index', compact('videos'));
    }

    public function update(Request $request, RequestedVideo $requested_video)
    {
        if($request->status == 'resolved'){
            $videos = RequestedVideo::where('title', $requested_video->title)->update(['status' => 1]);
        } elseif($request->status == 'removed'){
            $videos = RequestedVideo::where('title', $requested_video->title)->update(['status' => 2]);
        }
        $requested_video->save();

        return back();
    }
}