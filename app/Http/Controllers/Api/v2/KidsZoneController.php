<?php

namespace App\Http\Controllers\Api\v2;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use App\Models\Favorite;
use App\Models\Serie;
use App\Models\User;
use App\Models\Video;
use App\Models\VideoView;

class KidsZoneController extends Controller
{
	public function index(Request $request)
	{
		$user = User::where('access_token', $request->token)->first();

		$videos = Video::where('kids_zone', 1)->get();

		$series = Serie::where('kids_zone', 1)->get();

		$toddler_series = Serie::where('kids_zone', 2)->get();

		$toddler_videos = Video::where('kids_zone', 2)->get();

		return response()->json(['series' => $series, 'videos' => $videos, 'toddler_series' => $toddler_series, 'toddler_videos' => $toddler_videos]);
	}

    public function recentViewed(Request $request)
    {
        $user = User::where('access_token', $request->token)->first();
		$videos = VideoView::with(['video' => function($q) use($user) {
			$q->position1($user)->isFavorite1($user)->isSeen($user)->whereNotNull('kids_zone');
		}])->whereNotNull('video_id')->where('user_id', $user->id)->orderBy('updated_at', 'DESC')->limit(20)->get();
		$episodes = VideoView::with(['episode' => function($q)use($user){
            $q->isSeen($user)->groupBy('serie_id')->whereHas('serie', function($q1){
				$q1->whereNotNull('kids_zone');
			});
        }, 'episode.serie'])->whereNotNull('episode_id')->where('user_id', $user->id)->orderBy('updated_at', 'DESC')->limit(20)->get();

		return response()->json(['episodes' => $episodes, 'videos' => $videos]);
    }

	public function favorites(Request $request)
	{
		$user = User::where('access_token', $request->token)->first();
		$videos = Video::position1($user)->isFavorite1($user)->isSeen($user)->whereNotNull('kids_zone')->whereHas('favorites', function($q) use($user){
			$q->where('user_id', $user->id);
		})->get();

		$series = Serie::isFavorite1($user)->whereNotNull('kids_zone')->whereHas('favorites', function($q) use($user){
			$q->where('user_id', $user->id);
		})->get();
		
		return response()->json(['series' => $series, 'videos' => $videos]);
	}
}
