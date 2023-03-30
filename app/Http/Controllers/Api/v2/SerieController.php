<?php

namespace App\Http\Controllers\Api\v2;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Serie;
use App\Models\SerieCategory;
use App\Models\User;
use App\Models\VideoView;

class SerieController extends Controller
{
    public function index(Request $request)
	{
		$user = User::where('access_token', $request->token)->first();
		
        $serie_categories = SerieCategory::with(['series' => function($q) use($user){
            $q->isFavorite1($user)->orderBy('updated_at');
        }])->orderBy('order')->get();

		
		return response()->json($serie_categories);
	}

    public function mostWatched(Request $request)
    {
		$series = Serie::withCount(['video_views' => function($q) {
            $q->whereDate('video_view.created_at', '>', date('Y-m-d', time() - 30*86400));
        }])->orderBy('video_views_count', 'desc')->take(10)->get();

		return response()->json(['series' => $series]);
    }
}
