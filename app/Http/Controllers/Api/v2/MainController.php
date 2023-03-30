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

class MainController extends Controller
{
    public function recentViewed(Request $request)
    {
        $user = User::where('access_token', $request->token)->first();
		$videos = VideoView::with(['video' => function($q) use($user) {
			$q->position1($user)->isFavorite1($user)->isSeen($user);
		}])->whereNotNull('video_id')->where('user_id', $user->id)->orderBy('updated_at', 'DESC')->limit(20)->get();
		$episodes = VideoView::with(['episode' => function($q)use($user){
            $q->isSeen($user)->groupBy('serie_id');
        }, 'episode.serie'])->whereNotNull('episode_id')->where('user_id', $user->id)->orderBy('updated_at', 'DESC')->limit(20)->get();

		return response()->json(['episodes' => $episodes, 'videos' => $videos]);
    }

	public function recent(Request $request)
	{
		$user = User::where('access_token', $request->token)->first();

		$videos = Video::position1($user)->isFavorite1($user)->isSeen($user)->orderBy('created_at', 'desc')->take(30)->get();

		$series = Serie::isFavorite1($user)
			->join(DB::raw('(select serie_id, max(UNIX_TIMESTAMP(created_at)) as u from episodes group by serie_id) as e'), 'e.serie_id', '=', 'series.id')
			->orderBy('e.u', 'desc')
			->take(30)
			->get();

		return response()->json(['series' => $series, 'videos' => $videos]);
	}

	public function favorites(Request $request)
	{
		$user = User::where('access_token', $request->token)->first();
		$videos = Video::position1($user)->isFavorite1($user)->isSeen($user)->whereHas('favorites', function($q) use($user){
			$q->where('user_id', $user->id);
		})->get();

		$series = Serie::isFavorite1($user)->whereHas('favorites', function($q) use($user){
			$q->where('user_id', $user->id);
		})->get();
		
		return response()->json(['series' => $series, 'videos' => $videos]);
	}

	public function search(Request $request)
	{
		$user = User::where('access_token', $request->token)->first();

		$title_es1 = str_replace('s', 'z', $request->title);
		$title_es2 = str_replace('z', 's', $request->title);

		$videos = Video::position1($user)->isFavorite1($user)->isSeen($user)->where('title', 'like', '%'.$request->title.'%')
					->orWhere('title_es', 'like', '%'.$title_es1.'%')
					->orWhere('title_es', 'like', '%'.$title_es2.'%')
					->orWhereRaw('REPLACE(title, "-", " ") LIKE ?', ['%'.$request->title.'%'])
					->orWhereRaw('REPLACE(title_es, "-", " ") LIKE ?', ['%'.$title_es1.'%'])
					->orWhereRaw('REPLACE(title_es, "-", " ") LIKE ?', ['%'.$title_es2.'%'])
					->when((strtolower($request->title) == 'the' || strtolower($request->title) == 'the '), function($q) {
						return $q->take(5);
					})
					->get();

		$series = Serie::isFavorite1($user)->where('title', 'like', '%'.$request->title.'%')
						->orWhere('title_es', 'like', '%'.$title_es1.'%')
						->orWhere('title_es', 'like', '%'.$title_es2.'%')
						->orWhereRaw('REPLACE(title, "-", " ") LIKE ?', ['%'.$request->title.'%'])
						->orWhereRaw('REPLACE(title_es, "-", " ") LIKE ?', ['%'.$title_es1.'%'])
						->orWhereRaw('REPLACE(title_es, "-", " ") LIKE ?', ['%'.$title_es2.'%'])
						->when((strtolower($request->title) == 'the' || strtolower($request->title) == 'the '), function($q) {
							return $q->take(5);
						})
						->get();

		return response()->json(['videos' => $videos, 'series' => $series]);
	}
}
