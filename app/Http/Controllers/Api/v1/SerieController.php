<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Serie;
use App\Models\SerieCategory;
use App\Models\User;
use App\Models\Setting;
use App\Models\VideoView;

class SerieController extends Controller {

	public function index(Request $request)
	{
		$user = User::where('access_token', $request->token)->first();
		$settings = Setting::first();

		$serie_categories = DB::table('serie_categories')
			->orderBy('order')
			->select('id', 'name')
			->get();

		// $serie_categories = SerieCategory::with(['series'=>function($q){
		//     $q->orderBy('title');
		// }])->orderBy('order')->get();

		for($i = 0; $i < count($serie_categories); $i++){
			$series = DB::table('series')
				->where('serie_category_id', $serie_categories[$i]->id)
				->select(
					'id', 'title', 'plot', 'released_at', 'runtime', 'actors', 'director', 'imdb_rating', 'rating', 'views', 'featured_backdrop', 'hd', 'updated_at',
					DB::raw('CONCAT("https://s3.amazonaws.com/ctv3/", image) as image'),
					DB::raw('CONCAT("https://s3.amazonaws.com/ctv3/", backdrop) as backdrop'),
					DB::raw('0 as isFavorite')
				)
				->orderBy('updated_at', 'desc')
				->get();
			$serie_categories[$i]->series = $series;
		}

		$favorites = DB::table('favorites')->where('user_id', $user->id)->whereNotNull('serie_id')->select('serie_id')->get();
		foreach($favorites as $f){
			for($i=0;$i<count($serie_categories);$i++){
				for($j=0;$j<count($serie_categories[$i]->series);$j++){
					if($serie_categories[$i]->series[$j]->id == $f->serie_id){
						$serie_categories[$i]->series[$j]->isFavorite = 1;
					}
				}
			}
		}
		return response()->json($serie_categories);
	}

	public function serie(Serie $serie, Request $request)
	{
		$user = User::where('access_token', $request->token)->first();
		for ($i = 0; $i < count($serie->seasons); $i++) {
			$serie->seasons[$i]->poster = $serie->seasons[$i]->full_poster;
			for($j = 0; $j < count($serie->seasons[$i]->episodes); $j++){
				$serie->seasons[$i]->episodes[$j]->poster = $serie->seasons[$i]->episodes[$j]->full_poster;
				if($serie->seasons[$i]->episodes[$j]->hls_link == '' || $serie->seasons[$i]->episodes[$j]->hls_link == null){
					$serie->seasons[$i]->episodes[$j]->stream = $serie->seasons[$i]->episodes[$j]->stream1;
					$serie->seasons[$i]->episodes[$j]->streamFormat = $serie->seasons[$i]->episodes[$j]->stream_format1;
					$serie->seasons[$i]->episodes[$j]->subtitle_file_name = $serie->seasons[$i]->episodes[$j]->full_subtitle_file_name;
					$serie->seasons[$i]->episodes[$j]->subtitle_file_name_es = $serie->seasons[$i]->episodes[$j]->full_subtitle_file_name_es;
				}
				$serie->seasons[$i]->episodes[$j]->position = $serie->seasons[$i]->episodes[$j]->position($user);
				$serie->seasons[$i]->episodes[$j]->runtime = intval($serie->seasons[$i]->episodes[$j]->runtime);

				if($serie->seasons[$i]->episodes[$j]->hls_link != '' || $serie->seasons[$i]->episodes[$j]->hls_link != null){
					$serie->seasons[$i]->episodes[$j]->stream = $serie->seasons[$i]->episodes[$j]->hls_link;
					$serie->seasons[$i]->episodes[$j]->streamFormat = $serie->seasons[$i]->episodes[$j]->stream_formathls;
				}

				$serie->seasons[$i]->episodes[$j]->is_seen = $serie->seasons[$i]->episodes[$j]->is_seen($user);
				//$serie->seasons[$i]->episodes[$j]->updated_at = $serie->seasons[$i]->episodes[$j]->released_at;
			}
		}
		$seasons = $serie->seasons;
		return response()->json($seasons);
	}

	/**
	* initialize categories and series
	*
	* @param Request $request
	*/
	public function series_initialize(Request $request)
	{
		$user = User::where('access_token', $request->token)->first();
		$user_id = isset($user) ? $user->id : 0;
		$settings = Setting::first();

		$limit = intval($request->limit);
		$limit = empty($limit) ? 20 : $limit;

		// fetch all categories with counts
		$categories = DB::table('serie_categories')
			->join('series', 'serie_categories.id','=','series.serie_category_id')
			->select(
				DB::raw('serie_categories.id as id'),
				DB::raw('serie_categories.parent_id as category_parent_id'),
				DB::raw('serie_categories.name as name'),
				DB::raw('COUNT(*) as count')
				)
			->where(['series.active' => 1])
			->groupBy('serie_categories.id')
			->orderBy('serie_categories.order')
			->get();

		foreach ($categories as $key => $category) {
			$series = DB::table('series')
				->leftJoin('favorites', function($join) use ($user_id)
				{
					$join->on('series.id', '=', 'favorites.serie_id')
					->where('favorites.user_id', $user_id);
				})
				->where(['serie_category_id' => $category->id, 'series.active' => 1])
				->select(
					'series.id', 'title', 'plot', 'released_at', 'runtime', 'actors', 'director', 'imdb_rating', 'rating', 'views', 'featured_backdrop', 'hd', 'series.updated_at',
					DB::raw('CONCAT("https://s3.amazonaws.com/ctv3/", image) as image'),
					DB::raw('CONCAT("https://s3.amazonaws.com/ctv3/", backdrop) as backdrop'),
					DB::raw('IF(favorites.id IS NULL, 0, 1) as isFavorite')
				)
				->orderBy('series.updated_at', 'desc')
				->limit($limit)
				->get();

			for($k=0;$k<count($series);$k++){
				$serie = Serie::where('id', $series[$k]->id)->whereHas('seasons', function($q) use($user_id){
					$q->whereHas('episodes', function($q1) use($user_id){
						$q1->whereHas('episode_views', function($q2) use($user_id){
							$q2->where('user_id', $user_id);
						});
					});
				})->first();
				if($serie) {
					$series[$k]->is_seen = true;
				}
			}
			$categories[$key]->series = $series;
		}
		return response()->json($categories);
	}

	/**
	* fetch series slice in category
	*
	* @param Request $request
	*/
	public function series_get_slice(Request $request)
	{
		$user = User::where('access_token', $request->token)->first();
		$user_id = isset($user) ? $user->id : 0;
		$settings = Setting::first();

		$category_id = intval($request->id);
		$offset = intval($request->offset);
		$limit = intval($request->limit);
		$limit = empty($limit) ? 20 : $limit;

		// fetch category with counts
		$category = DB::table('serie_categories')
			->join('series', 'serie_categories.id','=','series.serie_category_id')
			->select(
				DB::raw('serie_categories.id as id'),
				DB::raw('serie_categories.parent_id as category_parent_id'),
				DB::raw('serie_categories.name as name'),
				DB::raw('COUNT(*) as count')
				)
			->where(['serie_categories.id' => $category_id, 'series.active' => 1])
			->groupBy('serie_categories.id')
			->first();

		if ($category) {
			$series = DB::table('series')
				->leftJoin('favorites', function($join) use ($user_id)
				{
					$join->on('series.id', '=', 'favorites.serie_id')
					->where('favorites.user_id', $user_id);
				})
				->where(['serie_category_id' => $category->id, 'series.active' => 1])
				->select(
					'series.id', 'title', 'plot', 'released_at', 'runtime', 'actors', 'director', 'imdb_rating', 'rating', 'views', 'featured_backdrop', 'hd',
					DB::raw('CONCAT("https://s3.amazonaws.com/ctv3/", image) as image'),
					DB::raw('CONCAT("https://s3.amazonaws.com/ctv3/", backdrop) as backdrop'),
					DB::raw('IF(favorites.id IS NULL, 0, 1) as isFavorite')
				)
				->orderBy('title')
				->limit($limit)
				->offset($offset)
				->get();

			$category->series = $series;
		} else {
			$category = array(
				'id' => $category_id,
				'category_parent_id' => null,
				'name' => '',
				'count' => 0,
				'series' => array()
			);
		}


		return response()->json($category);
	}
}
