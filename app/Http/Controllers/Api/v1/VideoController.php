<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Video;
use App\Models\User;
use App\Models\VideoCategory;
use App\Models\Setting;
use App\Models\VideoView;

class VideoController extends Controller {

	public function index(Request $request)
	{
		$user = User::where('access_token', $request->token)->first();
		$settings = Setting::first();

		$videos = DB::table('videos')
			->join('video_categories', 'video_categories.id','=','videos.video_category_id')
			->where('videos.active', 1)
			->orderBy('video_categories.order')
			->select(
				'videos.id', 'video_category_id', 'title', 'plot', 'released_at', 'featured', 'actors', 'director', 'imdb_rating', 'rating', 'hd', 'en', 'es', 'video_file_name',
				DB::raw('CONCAT("https://s3.amazonaws.com/ctv3/", image) as image'),
				DB::raw('CONCAT("https://s3.amazonaws.com/ctv3/", backdrop) as backdrop'),
				DB::raw('CONCAT("'.$settings->server_link.'", video_file_name) as stream'),
				DB::raw('"mp4" as streamFormat'),
				DB::raw('CONCAT("'.$settings->subtitle_link.'", subtitle_file_name) as subtitle_file_name'),
				DB::raw('CONCAT("'.$settings->subtitle_link.'", subtitle_file_name_es) as subtitle_file_name_es'),
				DB::raw('(runtime * 60) as runtime'),
				DB::raw('0 as position'),
				DB::raw('0 as isFavorite')
			)
			->get();

		for($i=0;$i<count($videos);$i++){
			$videos[$i]->streamFormat = substr($videos[$i]->video_file_name, strrpos($videos[$i]->video_file_name, '.') + 1);
		}

		for($i=0;$i<count($videos);$i++){
			$viewed = VideoView::where('video_id', $videos[$i]->id)->where('user_id', $user->id)->count();
			if($viewed){
				$videos[$i]->is_seen = true;
			} else {
				$videos[$i]->is_seen = false;
			}			
		}

		$positions = DB::table('video_view')->where('user_id', $user->id)->whereNotNull('video_id')->select('video_id', 'position')->get();
		foreach($positions as $p){
			for($i=0;$i<count($videos);$i++){
				if($videos[$i]->id == $p->video_id){
					$videos[$i]->position = $p->position;
				}
			}
		}

		$favorites = DB::table('favorites')->where('user_id', $user->id)->whereNotNull('video_id')->select('video_id')->get();
		foreach($favorites as $f){
			for($i=0;$i<count($videos);$i++){
				if($videos[$i]->id == $f->video_id){
					$videos[$i]->isFavorite = 1;
				}
			}
		}

		return response()->json($videos);
	}

	public function video(Video $video, Request $request)
	{
		$user = User::where('access_token', $request->token)->first();

		$video->image = $video->full_image;
		$video->backdrop = $video->full_backdrop;
		$video->stream = $video->stream;
		$video->streamFormat = $video->stream_format;
		$video->subtitle_file_name = $video->full_subtitle_file_name;
		$video->subtitle_file_name_es = $video->full_subtitle_file_name_es;
		$video->position = $video->position($user);
		$video->isFavorite = $video->isFavorite($user);
		$video->is_seen = $video->is_seen($user);

		return response()->json($video);
	}

	public function video_categories(Request $request){
		$video_categories = VideoCategory::where('parent_id', $request->topcategory)->orderBy('order')->get();
		return response()->json($video_categories);
	}

	public function video_category($id){
		$video_category = VideoCategory::find($id);
		return response()->json($video_category);
	}

	public function videoByCategory(Request $request)
	{
		$user = User::where('access_token', $request->token)->first();

		$videos = VideoCategory::with(['videos' => function($q){
			$q->where('active', 1);
		}])->where('id', $request->id)->first();

		for($i = 0; $i < count($videos->videos); $i++) {
			$videos->videos[$i]->image = $videos->videos[$i]->full_image;
			$videos->videos[$i]->backdrop = $videos->videos[$i]->full_backdrop;
			$videos->videos[$i]->stream = $videos->videos[$i]->stream;
			$videos->videos[$i]->streamFormat = $videos->videos[$i]->stream_format;
			$videos->videos[$i]->subtitle_file_name = $videos->videos[$i]->full_subtitle_file_name;
			$videos->videos[$i]->subtitle_file_name_es = $videos->videos[$i]->full_subtitle_file_name_es;
			$videos->videos[$i]->position = $videos->videos[$i]->position($user);
			$videos->videos[$i]->isFavorite = $videos->videos[$i]->isFavorite($user);
			$videos->videos[$i]->is_seen = $videos->videos[$i]->is_seen($user);
		}
		return response()->json($videos);
	}

	/**
	* initialize categories and videos
	*
	* @param Request $request
	*/
	public function videos_initialize(Request $request)
	{
		$user = User::where('access_token', $request->token)->first();
		$user_id = isset($user) ? $user->id : 0;
		$settings = Setting::first();

		$limit = intval($request->limit);
		$limit = empty($limit) ? 20 : $limit;

		// fetch all categories with counts
		$categories = DB::table('video_categories')
			->join('videos', 'video_categories.id','=','videos.video_category_id')
			->select(
				DB::raw('video_categories.id as video_category_id'),
				DB::raw('video_categories.parent_id as category_parent_id'),
				DB::raw('video_categories.name as category_name'),
				DB::raw('COUNT(*) as count')
				)
			->where(['videos.active' => 1])
			->groupBy('video_categories.id')
			->orderBy('video_categories.order')
			->get();

		foreach ($categories as $key => $category) {
			$videos = DB::table('videos')
				->leftJoin('video_view', function($join) use ($user_id)
				{
					$join->on('videos.id', '=', 'video_view.video_id')
					->where('video_view.user_id', $user_id);
				})
				->leftJoin('favorites', function($join) use ($user_id)
				{
					$join->on('videos.id', '=', 'favorites.video_id')
					->where('favorites.user_id', $user_id);
				})
				->where(['video_category_id' => $category->video_category_id, 'videos.active' => 1])
				->select(
					'videos.id', 'video_category_id', 'title', 'plot', 'released_at', 'featured', 'actors', 'director', 'imdb_rating', 'rating', 'hd', 'en', 'es', 'video_file_name',
					DB::raw('CONCAT("https://s3.amazonaws.com/ctv3/", image) as image'),
					DB::raw('CONCAT("https://s3.amazonaws.com/ctv3/", backdrop) as backdrop'),
					DB::raw('CONCAT("'.$settings->server_link.'", video_file_name) as stream'),
					DB::raw('"mp4" as streamFormat'),
					DB::raw('CONCAT("'.$settings->subtitle_link.'", subtitle_file_name) as subtitle_file_name'),
					DB::raw('CONCAT("'.$settings->subtitle_link.'", subtitle_file_name_es) as subtitle_file_name_es'),
					DB::raw('(runtime * 60) as runtime'),
					DB::raw('IFNULL(video_view.position, 0) as position'),
					DB::raw('IF(favorites.id IS NULL, 0, 1) as isFavorite')
				)
				->orderBy('videos.created_at', 'DESC')
				->orderBy('title')
				->limit($limit)
				->get();

			for($i=0;$i<count($videos);$i++){
				$viewed = VideoView::where('video_id', $videos[$i]->id)->where('user_id', $user->id)->count();
				if($viewed){
					$videos[$i]->is_seen = true;
				} else {
					$videos[$i]->is_seen = false;
				}			
				$videos[$i]->streamFormat = substr($videos[$i]->video_file_name, strrpos($videos[$i]->video_file_name, '.') + 1);
			}

			$categories[$key]->videos = $videos;
		}

		return response()->json($categories);
	}

	/**
	* fetch videos slice in category
	*
	* @param Request $request
	*/
	public function videos_get_slice(Request $request)
	{
		$user = User::where('access_token', $request->token)->first();
		$user_id = isset($user) ? $user->id : 0;
		$settings = Setting::first();

		$category_id = intval($request->id);
		$offset = intval($request->offset);
		$limit = intval($request->limit);
		$limit = empty($limit) ? 20 : $limit;

		// fetch category with counts
		$category = DB::table('video_categories')
			->join('videos', 'video_categories.id','=','videos.video_category_id')
			->select(
				DB::raw('video_categories.id as video_category_id'),
				DB::raw('video_categories.parent_id as category_parent_id'),
				DB::raw('video_categories.name as category_name'),
				DB::raw('COUNT(*) as count')
				)
			->where(['video_categories.id' => $category_id, 'videos.active' => 1])
			->groupBy('video_categories.id')
			->first();

		$videos = DB::table('videos')
			->leftJoin('video_view', function($join) use ($user_id)
			{
				$join->on('videos.id', '=', 'video_view.video_id')
				->where('video_view.user_id', $user_id);
			})
			->leftJoin('favorites', function($join) use ($user_id)
			{
				$join->on('videos.id', '=', 'favorites.video_id')
				->where('favorites.user_id', $user_id);
			})
			->where(['video_category_id' => $category->video_category_id, 'videos.active' => 1])
			->select(
				'videos.id', 'video_category_id', 'title', 'plot', 'released_at', 'featured', 'actors', 'director', 'imdb_rating', 'rating', 'hd', 'en', 'es', 'video_file_name',
				DB::raw('CONCAT("https://s3.amazonaws.com/ctv3/", image) as image'),
				DB::raw('CONCAT("https://s3.amazonaws.com/ctv3/", backdrop) as backdrop'),
				DB::raw('CONCAT("'.$settings->server_link.'", video_file_name) as stream'),
				DB::raw('"mp4" as streamFormat'),
				DB::raw('CONCAT("'.$settings->subtitle_link.'", subtitle_file_name) as subtitle_file_name'),
				DB::raw('CONCAT("'.$settings->subtitle_link.'", subtitle_file_name_es) as subtitle_file_name_es'),
				DB::raw('(runtime * 60) as runtime'),
				DB::raw('IFNULL(video_view.position, 0) as position'),
				DB::raw('IF(favorites.id IS NULL, 0, 1) as isFavorite')
			)
			->orderBy('videos.created_at', 'DESC')
			->orderBy('title')
			->limit($limit)
			->offset($offset)
			->get();

		for($i=0;$i<count($videos);$i++){
			$videos[$i]->streamFormat = substr($videos[$i]->video_file_name, strrpos($videos[$i]->video_file_name, '.') + 1);
		}

		$category->videos = $videos;

		return response()->json($category);
	}
}