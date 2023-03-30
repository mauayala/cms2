<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\Serie;
use App\Models\Season;
use App\Models\SerieCategory;
use App\Models\User;
use App\Models\Favorite;
use App\Models\Video;
use App\Models\VideoView;
use App\Models\Error;
use App\Models\ErrorUser;
use App\Models\Message;
use App\Models\RequestedVideo;
use App\Models\Episode;
use App\Models\Setting;

class MainController extends Controller {

	private $apikey = '5fabc059c6c919ad8fa7014c1c844cf0';

	public function request_search(Request $request)
	{
		$ch = curl_init();
		curl_setopt($ch,CURLOPT_URL,"https://api.themoviedb.org/3/search/multi?api_key=".$this->apikey."&query=".$request->title);
		curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
		$output=curl_exec($ch);
		$outputs = json_decode($output);
		$result = [];
		if(isset($outputs->results)){
			foreach ($outputs->results as $output) {
				if(isset($output->poster_path)){
					$output->poster_path = 'https://image.tmdb.org/t/p/w500'.$output->poster_path;
				}
				$result[] = $output;
			}
		}

		return $result;
	}

	public function search(Request $request)
	{
		$user = User::where('access_token', $request->token)->first();

		$title_es1 = str_replace('s', 'z', $request->title);
		$title_es2 = str_replace('z', 's', $request->title);

		//\DB::enableQueryLog();
		$videos = Video::where('title', 'like', '%'.$request->title.'%')
					->orWhere('title_es', 'like', '%'.$title_es1.'%')
					->orWhere('title_es', 'like', '%'.$title_es2.'%')
					->orWhereRaw('REPLACE(title, "-", " ") LIKE ?', ['%'.$request->title.'%'])
					->orWhereRaw('REPLACE(title_es, "-", " ") LIKE ?', ['%'.$title_es1.'%'])
					->orWhereRaw('REPLACE(title_es, "-", " ") LIKE ?', ['%'.$title_es2.'%'])
					->get();
		//dd(\DB::getQueryLog());
		for($i = 0; $i < count($videos); $i++){
			$videos[$i]->image = $videos[$i]->full_image;
			$videos[$i]->backdrop = $videos[$i]->full_backdrop;
			$videos[$i]->stream = $videos[$i]->stream;
			$videos[$i]->streamFormat = $videos[$i]->stream_format;
			$videos[$i]->subtitle_file_name = $videos[$i]->full_subtitle_file_name;
			$videos[$i]->subtitle_file_name_es = $videos[$i]->full_subtitle_file_name_es;
			$videos[$i]->position = $videos[$i]->position($user);
			$videos[$i]->isFavorite = $videos[$i]->isFavorite($user);
			$videos[$i]->is_seen = $videos[$i]->is_seen($user);
		}

		$series = Serie::where('title', 'like', '%'.$request->title.'%')
						->orWhere('title_es', 'like', '%'.$title_es1.'%')
						->orWhere('title_es', 'like', '%'.$title_es2.'%')
						->orWhereRaw('REPLACE(title, "-", " ") LIKE ?', ['%'.$request->title.'%'])
						->orWhereRaw('REPLACE(title_es, "-", " ") LIKE ?', ['%'.$title_es1.'%'])
						->orWhereRaw('REPLACE(title_es, "-", " ") LIKE ?', ['%'.$title_es2.'%'])
						->get();
		for ($i=0; $i < count($series); $i++) {
			$series[$i]->image = $series[$i]->full_image;
			$series[$i]->backdrop = $series[$i]->full_backdrop;
			$series[$i]->isFavorite = $series[$i]->isFavorite($user);
		}

		return response()->json(['videos' => $videos, 'series' => $series]);
	}

	public function request_video(Request $request)
	{
		$requested_video = new RequestedVideo;
		$requested_video->title = $request->title;
		if($requested_video->save())
			return response()->json(['type' => 'success']);

		return response()->json(['type' => 'error']);
	}

	public function change_pin(Request $request)
	{
		$user = User::where('access_token', $request->token)->first();

		$user->pin = $request->pin;
		if($user->save())
			return response()->json(['type' => 'success']);

		return response()->json(['type' => 'error']);
	}

	public function change_password(Request $request)
	{
		$user = User::where('access_token', $request->token)->first();

		$user->password = Hash::make($request->password);
		if($user->save())
			return response()->json(['type' => 'success']);

		return response()->json(['type' => 'error']);
	}

	public function change_parent_control(Request $request)
	{
		$user = User::where('access_token', $request->token)->first();

		$user->parent_control = $request->parent_control;
		if($user->save())
			return response()->json(['type' => 'success']);

		return response()->json(['type' => 'error']);
	}

	public function set_favorite(Request $request)
	{
		$user = User::where('access_token', $request->token)->first();
		$favorite = new Favorite;
		$favorite->user_id = $user->id;

		isset($request->serie_id) ? $favorite->serie_id = $request->serie_id : $favorite->video_id = $request->video_id;

		if($favorite->save())
			return response()->json(['type' => 'success']);

		return response()->json(['type' => 'error']);
	}

	public function delete_favorite(Request $request)
	{
		$user = User::where('access_token', $request->token)->first();
		if(isset($request->serie_id)){
			$favorite = Favorite::where('user_id', $user->id)->where('serie_id', $request->serie_id)->delete();
		} else {
			$favorite = Favorite::where('user_id', $user->id)->where('video_id', $request->video_id)->delete();
		}
		if($favorite)
			return response()->json(['type' => 'success']);

		return response()->json(['type' => 'error']);
	}

	public function favorite_videos(Request $request)
	{
		$user = User::where('access_token', $request->token)->first();
		$favorites = Favorite::where('user_id', $user->id)->where('video_id', '<>', null)->get();
		$videos = [];
		foreach ($favorites as $favorite) {
			if($favorite->video){
				$videos[] = $favorite->video;
			}
		}
		for($i = 0; $i < count($videos); $i++){
			$videos[$i]->image = $videos[$i]->full_image;
			$videos[$i]->backdrop = $videos[$i]->full_backdrop;
			$videos[$i]->stream = $videos[$i]->stream;
			$videos[$i]->streamFormat = $videos[$i]->stream_format;
			$videos[$i]->subtitle_file_name = $videos[$i]->full_subtitle_file_name;
			$videos[$i]->subtitle_file_name_es = $videos[$i]->full_subtitle_file_name_es;
			$videos[$i]->position = $videos[$i]->position($user);
			$videos[$i]->isFavorite = $videos[$i]->isFavorite($user);
			$videos[$i]->is_seen = $videos[$i]->is_seen($user);
		}
		return response()->json($videos);
	}

	public function favorite_series(Request $request)
	{
		$user = User::where('access_token', $request->token)->first();
		$favorites = Favorite::where('user_id', $user->id)->where('serie_id', '<>', null)->get();
		$series = [];
		foreach ($favorites as $favorite) {
			if($favorite->serie){
				$series[] = $favorite->serie;
			}
		}
		for ($i=0; $i < count($series); $i++) {
			$series[$i]->image = $series[$i]->full_image;
			$series[$i]->backdrop = $series[$i]->full_backdrop;
			$series[$i]->isFavorite = $series[$i]->isFavorite($user);
		}
		return response()->json($series);
	}

	public function recent(Request $request)
	{
		$user = User::where('access_token', $request->token)->first();

		$videos = Video::where('created_at', '>', date('Y-m-d H:i:s', time()-24*60*60*30))->limit(50)->orderBy('id', 'desc')->get();
		for($i = 0; $i < count($videos); $i++){
			$videos[$i]->image = $videos[$i]->full_image;
			$videos[$i]->backdrop = $videos[$i]->full_backdrop;
			$videos[$i]->stream = $videos[$i]->stream;
			$videos[$i]->streamFormat = $videos[$i]->stream_format;
			$videos[$i]->subtitle_file_name = $videos[$i]->full_subtitle_file_name;
			$videos[$i]->subtitle_file_name_es = $videos[$i]->full_subtitle_file_name_es;
			$videos[$i]->position = $videos[$i]->position($user);
			$videos[$i]->isFavorite = $videos[$i]->isFavorite($user);
			$videos[$i]->is_seen = $videos[$i]->is_seen($user);
		}
		// select series.* from series
		// inner join (
		// 	select serie_id, max(UNIX_TIMESTAMP(updated_at)) as u from episodes group by serie_id
		// ) e on e.serie_id = series.id order by e.u desc limit 0, 50
		$series = Serie::select(DB::raw('series.*'))
			->join(DB::raw('(select serie_id, max(UNIX_TIMESTAMP(created_at)) as u from episodes group by serie_id) as e'), 'e.serie_id', '=', 'series.id')
			->orderBy('e.u', 'desc')
			->limit(50)
			->get();
		for ($i=0; $i < count($series); $i++) {
			$series[$i]->image = $series[$i]->full_image;
			$series[$i]->backdrop = $series[$i]->full_backdrop;
			$series[$i]->isFavorite = $series[$i]->isFavorite($user);
		}

		return response()->json(['series' => $series, 'videos' => $videos]);
	}

	public function featured(Request $request)
	{
		$expiredvideos = Video::where('featured', 1)->where('updated_at', '<', date('Y-m-d H:i:s', time()-24*60*60*3))->get();
		foreach ($expiredvideos as $video) {
			$video->featured = 0;
			$video->save();
		}
		$expiredseries = Episode::where('featured', 1)->where('updated_at', '<', date('Y-m-d H:i:s', time()-24*60*60*3))->get();
		foreach ($expiredseries as $serie) {
			$serie->featured = 0;
			$serie->save();
		}

		$user = User::where('access_token', $request->token)->first();
		$settings = Setting::first();

		$videos = Video::where('featured', 1)->where('updated_at', '>=', date('Y-m-d H:i:s', time()-24*60*60*3))->get();
		for($i = 0; $i < count($videos); $i++){
			$videos[$i]->image = $videos[$i]->full_image;
			$videos[$i]->backdrop = $videos[$i]->full_backdrop;
			$videos[$i]->stream = $videos[$i]->stream;
			$videos[$i]->streamFormat = $videos[$i]->stream_format;
			$videos[$i]->subtitle_file_name = $videos[$i]->full_subtitle_file_name;
			$videos[$i]->subtitle_file_name_es = $videos[$i]->full_subtitle_file_name_es;
			$videos[$i]->position = $videos[$i]->position($user);
			$videos[$i]->isFavorite = $videos[$i]->isFavorite($user);
			$videos[$i]->is_seen = $videos[$i]->is_seen($user);
		}

		$series = DB::table('series')
			->join('episodes', 'series.id','=','episodes.serie_id')
			->where('episodes.featured', 1)
			->whereNotNull('series.featured_backdrop')
			->select('series.id', 'series.title', 'series.serie_category_id', 'series.backdrop', 'series.featured_backdrop')
			->get();

		for ($i=0; $i < count($series); $i++) {
			$series[$i]->backdrop = 'https://s3.amazonaws.com/ctv3/'.$series[$i]->backdrop;
			$series[$i]->featured_backdrop = 'https://s3.amazonaws.com/ctv3/'.$series[$i]->featured_backdrop;
			$series[$i]->isFavorite = Favorite::where('user_id', $user->id)->where('serie_id', $series[$i]->id)->count();

			$seasons = DB::table('seasons')
				->where('seasons.serie_id', $series[$i]->id)
				->select('seasons.id', 'seasons.poster', 'seasons.season_number')
				->get();

			for($j = 0; $j < count($seasons); $j++){
				$series[$i]->seasons[$j] = new \stdClass();
				$series[$i]->seasons[$j]->season_number = $seasons[$j]->season_number;
				$series[$i]->seasons[$j]->poster = 'https://s3.amazonaws.com/ctv3/'.pathinfo($seasons[$j]->poster, PATHINFO_DIRNAME).'/'.pathinfo($seasons[$j]->poster, PATHINFO_FILENAME) . '-small.' . pathinfo($seasons[$j]->poster, PATHINFO_EXTENSION);

				$episodes = Episode::where('season_id', $seasons[$j]->id)->get();
				for($k = 0; $k < count($episodes); $k++){
					$series[$i]->seasons[$j]->episodes[$k] = new \stdClass();
					$series[$i]->seasons[$j]->episodes[$k]->id = $episodes[$k]->id;
					$series[$i]->seasons[$j]->episodes[$k]->title = $episodes[$k]->title;
					$series[$i]->seasons[$j]->episodes[$k]->featured = $episodes[$k]->featured;
					$series[$i]->seasons[$j]->episodes[$k]->plot = $episodes[$k]->plot;
					$series[$i]->seasons[$j]->episodes[$k]->runtime = $episodes[$k]->runtime;
					$series[$i]->seasons[$j]->episodes[$k]->hd = $episodes[$k]->hd;
					$series[$i]->seasons[$j]->episodes[$k]->imdb_rating = $episodes[$k]->imdb_rating;
					$series[$i]->seasons[$j]->episodes[$k]->en = $episodes[$k]->en;
					$series[$i]->seasons[$j]->episodes[$k]->es = $episodes[$k]->es;
					$series[$i]->seasons[$j]->episodes[$k]->rating = $episodes[$k]->rating;
					$series[$i]->seasons[$j]->episodes[$k]->episode_number = $episodes[$k]->episode_number;
					$series[$i]->seasons[$j]->episodes[$k]->position = $episodes[$k]->position($user);
					$series[$i]->seasons[$j]->episodes[$k]->is_seen = $episodes[$k]->is_seen($user);
					$series[$i]->seasons[$j]->episodes[$k]->stream = $episodes[$k]->stream;
					$series[$i]->seasons[$j]->episodes[$k]->streamFormat = $episodes[$k]->stream_format;
					$series[$i]->seasons[$j]->episodes[$k]->subtitle_file_name = $episodes[$k]->full_subtitle_file_name;
					$series[$i]->seasons[$j]->episodes[$k]->subtitle_file_name_es = $episodes[$k]->full_subtitle_file_name_es;
				}
			}
		}

		return response()->json(['series' => $series, 'videos' => $videos]);
	}

	public function videoposition(Request $request)
	{
		$user = User::where('access_token', $request->token)->first();

		if(isset($request->video_id)){
			$view = VideoView::where('video_id', $request->video_id)->where('user_id', $user->id)->first();
		} else {
			$view = VideoView::where('episode_id', $request->episode_id)->where('user_id', $user->id)->first();
		}

		if($view){
			$view->position = $request->position;
		} else {
			$view = new VideoView;
			$view->position = $request->position;
			if(isset($request->video_id)){
				$view->video_id = $request->video_id;
			} else {
				$view->episode_id = $request->episode_id;
			}
			$view->user_id = $user->id;
		}

		if($view->save())
			return response()->json(['type' => 'success']);

		return response()->json(['type' => 'error']);
	}

	public function recent_viewed(Request $request)
	{
		$user = User::where('access_token', $request->token)->first();
		$videos = VideoView::whereNotNull('video_id')->where('user_id', $user->id)->orderBy('updated_at', 'DESC')->limit(20)->get();
		$episodes = VideoView::whereNotNull('episode_id')->where('user_id', $user->id)->orderBy('updated_at', 'DESC')->limit(20)->get();

		$raw_videos = [];
		foreach ($videos as $key => $video) {
			if (isset($video->video)) {
				$video->video->image = $video->video->full_image;
				$video->video->backdrop = $video->video->full_backdrop;
				$video->video->stream = $video->video->stream;
				$video->video->streamFormat = $video->video->stream_format;
				$video->video->subtitle_file_name = $video->video->full_subtitle_file_name;
				$video->video->subtitle_file_name_es = $video->video->full_subtitle_file_name_es;
				$video->video->position = $video->video->position($user);
				$video->video->isFavorite = $video->video->isFavorite($user);
				$video->video->is_seen = $video->video->is_seen($user);
				$videos[$key]->video = $video->video;
				array_push($raw_videos, $videos[$key]);
			} else {
				unset($videos[$key]);
			}
		}

		// for($i = 0; $i < count($videos); $i++){
		// 	if (isset($videos[$i]->video)) {
		// 		$videos[$i]->video->image = $videos[$i]->video->full_image;
		// 		$videos[$i]->video->backdrop = $videos[$i]->video->full_backdrop;
		// 		$videos[$i]->video->stream = $videos[$i]->video->stream;
		// 		$videos[$i]->video->streamFormat = $videos[$i]->video->stream_format;
		// 		$videos[$i]->video->subtitle_file_name = $videos[$i]->video->full_subtitle_file_name;
		// 		$videos[$i]->video->subtitle_file_name_es = $videos[$i]->video->full_subtitle_file_name_es;
		// 		$videos[$i]->video->position = $videos[$i]->video->position($user);
		// 		$videos[$i]->video->isFavorite = $videos[$i]->video->isFavorite($user);
		// 		$videos[$i]->video->is_seen = $videos[$i]->video->is_seen($user);
		// 		$videos[$i]->video = $videos[$i]->video;
		// 	} else {
		// 		unset($videos[$i]);
		// 	}
		// }

		for($i = 0; $i < count($episodes); $i++){
			if(isset($episodes[$i]->episode->season)){
				$episodes[$i]->episode->is_seen = $episodes[$i]->episode->is_seen($user);
				$episodes[$i]->episode->poster = $episodes[$i]->episode->full_poster;
				$episodes[$i]->episode->stream = $episodes[$i]->episode->stream1;
				$episodes[$i]->episode->streamFormat = $episodes[$i]->episode->stream_format1;
				$episodes[$i]->episode->subtitle_file_name = $episodes[$i]->episode->full_subtitle_file_name;
				$episodes[$i]->episode->subtitle_file_name_es = $episodes[$i]->episode->full_subtitle_file_name_es;
				$episodes[$i]->episode->season_number = $episodes[$i]->episode->season->season_number;
				unset($episodes[$i]->episode->season);
				$episodes[$i]->episode = $episodes[$i]->episode;
				$episodes[$i]->episode->serie = $episodes[$i]->episode->serie;
				$episodes[$i]->episode->serie->image = $episodes[$i]->episode->serie->full_image;
				$episodes[$i]->episode->serie->backdrop = $episodes[$i]->episode->serie->full_backdrop;
				$episodes[$i]->episode->serie->featured_backdrop = $episodes[$i]->episode->serie->full_featured_backdrop;
			}
		}
		return response()->json(['episodes' => $episodes, 'videos' => $raw_videos]);
	}

	public function error(Request $request)
	{
		$user = User::where('access_token', $request->token)->first();

		if(isset($request->video_id)){
			$found = Error::where('type', $request->type)->where('video_id', $request->video_id)->where('status', 0)->first();
		} else {
			$found = Error::where('type', $request->type)->where('episode_id', $request->episode_id)->where('status', 0)->first();
		}

		if($found){
			$error = new ErrorUser;
			$error->user_id = $user->id;
			$error->error_id = $found->id;
			if($error->save())
				return response()->json(['type' => 'success']);
		} else {
			$error = new Error;
			$error->type = $request->type;
			if(isset($request->video_id)){
				$error->video_id = $request->video_id;
			} else {
				$error->episode_id = $request->episode_id;
			}

			if($error->save()){
				$erroruser = new ErrorUser;
				$erroruser->user_id = $user->id;
				$erroruser->error_id = $error->id;
				if($erroruser->save())
					return response()->json(['type' => 'success']);
			}
		}

		return response()->json(['type' => 'error']);
	}

	public function message(Request $request)
	{
		$user = User::where('access_token', $request->token)->first();

		$messages = Message::where('user_id', $user->id)->where('status', 0)->get();
		$messages_all = Message::where('user_id', 0)->where('status', 0)->get();

		return response()->json(['private'=>$messages, 'broadcast'=>$messages_all]);
	}

	public function read_message(Request $request){
		$user = User::where('access_token', $request->token)->first();

		$message = Message::find($request->message_id);
		$message->status = 1;
		if($message->save())
			return response()->json(['type' => 'success']);

		return response()->json(['type' => 'error']);
	}

	public function settings()
	{
		return Setting::select('ip_domain', 'live_username', 'live_password')->first();
	}

	public function substatus(Request $request)
	{
		$user = User::where('access_token', $request->token)->first();
		return $user->trial_ends_at;
	}
}
