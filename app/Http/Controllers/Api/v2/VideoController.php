<?php

namespace App\Http\Controllers\Api\v2;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Video;

class VideoController extends Controller
{
    public function index(Request $request)
	{
		$user = User::where('access_token', $request->token)->first();

		$videos = Video::select('videos.*')->position1($user)->isFavorite1($user)->isSeen($user)
			->join('video_categories', 'video_categories.id','=','videos.video_category_id')
			->where('active', 1)
			->orderBy('video_categories.order')
            ->take(100)
			->get();

		return response()->json($videos);
	}

    public function mostWatched(Request $request)
    {
        $user = User::where('access_token', $request->token)->first();
		
		$videos = Video::withCount(['video_views' => function($q) {
                $q->whereDate('video_view.created_at', '>', date('Y-m-d', time() - 30*86400));
            }])->orderBy('video_views_count', 'desc')->take(10)->get();

		return response()->json(['videos' => $videos]);
    }
}
