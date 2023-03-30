<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Libraries\ImageHandler;
use App\Models\Serie;
use App\Models\Season;
use App\Models\Episode;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManagerStatic as Image;

class EpisodeController extends Controller {

    public function index(Serie $series, Season $season, Request $request)
    {
        $episodes = Episode::where('season_id', $season->id)->orderBy('episode_number')->get();
        
        return view('series.episodes.index', compact('episodes', 'season', 'series'));
    }

    public function create(Serie $series, Season $season)
    {
        $list = '';
        if(!empty($season->actors)){
            $actors = explode(',', $season->actors);
            $i=0;            
            foreach($actors as $actor){
                if($i < 5){
                    $list .= $actor.',';
                }
                $i++;
            }
        }
        $actors = $list;

        return view('series.episodes.create', compact('series', 'season', 'actors'));
    }

    public function store(Serie $series, Season $season, Request $request)
    {
        $data = $request->all();
        $data['serie_id'] = $series->id;
        $data['season_id'] = $season->id;

        if($request->hls_link != ''){
            $data['serie_file_name'] = '';
            $data['subtitle_file_name'] = '';
            $data['subtitle_file_name_es'] = '';
        }

        $series->updated_at = date('Y-m-d H:i:s');
        $series->save();
        
        $month_year = date('FY').'/';
        $s3 = Storage::disk('s3');

        if (isset($data['image'])) {   
            $filename = basename($_FILES['image']['name']);            
            $small_filename = pathinfo($filename, PATHINFO_FILENAME) . '-small.' . pathinfo($filename, PATHINFO_EXTENSION);
            $data['poster'] = $month_year.$filename;
            $img = Image::make($request->file('image')->getRealPath())->save(public_path('/public/images/' . $small_filename));
            chmod(public_path('/public/images/' . $small_filename), 0777);
            $s3->put($month_year.$small_filename, file_get_contents(env('APP_URL').'/public/images/'.$small_filename));
            unlink(public_path('/public/images/' . $small_filename));
        } elseif(isset($data['image_link']) && !empty($data['image_link']) && $data['image_link'] != 'https://image.tmdb.org/t/p/w500null') {
            $s3->put($month_year.basename($data['image_link']), file_get_contents($data['image_link']));
            $data['poster'] = $month_year.basename($data['image_link']);

            file_put_contents($_SERVER['DOCUMENT_ROOT'].'/public/images/'.basename($data['image_link']), file_get_contents($data['image_link']));
            chmod($_SERVER['DOCUMENT_ROOT'].'/public/images/'.basename($data['image_link']), 0777);
            $filename = basename($data['image_link']);
            $small_filename = pathinfo($filename, PATHINFO_FILENAME) . '-small.' . pathinfo($filename, PATHINFO_EXTENSION);
            $img = Image::make(public_path('/public/images/'.$filename))->resize(320, null, function ($constraint) {
                $constraint->aspectRatio();
            })->save(public_path('/public/images/' . $small_filename));
            chmod(public_path('/public/images/' . $small_filename), 0777);
            $s3->put($month_year.$small_filename, file_get_contents(env('APP_URL').'/public/images/'.$small_filename));
            unlink(public_path('/public/images/' . $small_filename));
            unlink(public_path('/public/images/'.$filename));
        } else {
            $data['poster'] = 'placeholder.jpg';
        }

        if(empty($data['featured'])){
            $data['featured'] = 0;
        }

        if(empty($data['en'])){
            $data['en'] = 0;
        }

        if(empty($data['es'])){
            $data['es'] = 0;
        }

        $episode = Episode::create($data);
        $this->checkLinks($episode);

        return redirect('dashboard/series/'.$series->id.'/seasons/'.$season->id.'/episodes')->with(['note' => 'New Serie Successfully Added!', 'note_type' => 'success']);
    }

    public function manualcreate(Serie $series, Season $season)
    {
        return view('series.episodes.manualcreate', compact('series', 'season'));
    }

    public function manualstore(Serie $series, Season $season, Request $request)
    {
        $data = $request->all();
        $data['serie_id'] = $series->id;
        $data['season_id'] = $season->id;
        
        $month_year = date('FY').'/';
        $s3 = Storage::disk('s3');

        if (isset($data['image'])) {
            $filename = basename($_FILES['image']['name']);            
            $small_filename = pathinfo($filename, PATHINFO_FILENAME) . '-small.' . pathinfo($filename, PATHINFO_EXTENSION);
            $data['poster'] = $month_year.$filename;
            $img = Image::make($request->file('image')->getRealPath())->save(public_path('/public/images/' . $small_filename));
            chmod(public_path('/public/images/' . $small_filename), 0777);
            $s3->put($month_year.$small_filename, file_get_contents(env('APP_URL').'/public/images/'.$small_filename));
            unlink(public_path('/public/images/' . $small_filename));
        } else {
            $data['poster'] = 'placeholder.jpg';
        }

        if(empty($data['featured'])){
            $data['featured'] = 0;
        }

        if(empty($data['en'])){
            $data['en'] = 0;
        }

        if(empty($data['es'])){
            $data['es'] = 0;
        }

        $data['runtime'] = $data['runtime']*60;
        $season_number = $season->season_number;
        if($season_number < 10){
            $season_number = '0'.$season_number;
        }
        $seriename = str_replace("'",'',str_replace('-','.',str_replace(':','.',str_replace(' ','.',strtolower($series->title))))).'.s'.$season_number.'e';

        for($i = $data['episode_start']; $i <= $data['episode_end']; $i++){
            if($i < 10){
                $data['serie_file_name'] = $seriename.'0'.$i.'.mp4';
                $data['subtitle_file_name'] = $seriename.'0'.$i.'.en.srt';
                $data['subtitle_file_name_es'] = $seriename.'0'.$i.'.es.srt';
            } else {
                $data['serie_file_name'] = $seriename.$i.'.mp4';
                $data['subtitle_file_name'] = $seriename.$i.'.en.srt';
                $data['subtitle_file_name_es'] = $seriename.$i.'.es.srt';
            }
            $data['title'] = 'Episodio '.$i;            
            $data['episode_number'] = $i;
            $episode = Episode::create($data);

            $this->checkLinks($episode);
        }       

        return redirect('dashboard/series/'.$series->id.'/seasons/'.$season->id.'/episodes')->with(['note' => 'New Serie Successfully Added!', 'note_type' => 'success']);
    }

    public function edit(Serie $series, Season $season, Episode $episode)
    {
        $list = '';
        if(!empty($season->actors)){
            $actors = explode(',', $season->actors);
            $i=0;            
            foreach($actors as $actor){
                if($i < 5){
                    $list .= $actor.',';
                }
                $i++;
            }
        }
        $actors = $list;
        return view('series.episodes.edit', compact('series', 'season', 'episode', 'actors'));
    }

    public function update(Serie $series, Season $season, Episode $episode, Request $request)
    {
        $all = $request->all();

        if($request->hls_link != ''){
            $all['serie_file_name'] = '';
            $all['subtitle_file_name'] = '';
            $all['subtitle_file_name_es'] = '';
        }

        if(!isset($all['episode_number'])){
            $all['episode_number'] = $episode->episode_number;
        }
        
        if(empty($all['featured'])){
            $all['featured'] = 0;
        }

        if(empty($all['en'])){
            $all['en'] = 0;
        }

        if(empty($all['es'])){
            $all['es'] = 0;
        }

        $month_year = date('FY').'/';
        $s3 = Storage::disk('s3');

        if (isset($all['image'])) {
            $filename = basename($_FILES['image']['name']);
            $s3->put($month_year.$filename, file_get_contents($request->file('image')));
            $all['poster'] = $month_year.$filename;
          
            $small_filename = pathinfo($filename, PATHINFO_FILENAME) . '-small.' . pathinfo($filename, PATHINFO_EXTENSION);
            $img = Image::make($request->file('image')->getRealPath())->save(public_path('/public/images/' . $small_filename));
            chmod(public_path('/public/images/' . $small_filename), 0777);
            $s3->put($month_year.$small_filename, file_get_contents(env('APP_URL').'/public/images/'.$small_filename));
            unlink(public_path('/public/images/' . $small_filename));
        } elseif(isset($all['image_link']) && !empty($all['image_link'])) {
            $s3->put($month_year.basename($all['image_link']), file_get_contents($all['image_link']));
            $all['poster'] = $month_year.basename($all['image_link']);

            file_put_contents($_SERVER['DOCUMENT_ROOT'].'/public/images/'.basename($all['image_link']), file_get_contents($all['image_link']));
            chmod($_SERVER['DOCUMENT_ROOT'].'/public/images/'.basename($all['image_link']), 0777);
            $filename = basename($all['image_link']);
            $small_filename = pathinfo($filename, PATHINFO_FILENAME) . '-small.' . pathinfo($filename, PATHINFO_EXTENSION);
            $img = Image::make(public_path('/public/images/'.$filename))->resize(320, null, function ($constraint) {
                $constraint->aspectRatio();
            })->save(public_path('/public/images/' . $small_filename));
            chmod(public_path('/public/images/' . $small_filename), 0777);
            $s3->put($month_year.$small_filename, file_get_contents(env('APP_URL').'/public/images/'.$small_filename));
            unlink(public_path('/public/images/' . $small_filename));
            unlink(public_path('/public/images/'.$filename));
        } else {
            $all['poster'] = $episode->poster;
        }
        
        $all['edited_by'] = \Auth::user()->id;
        $episode->update($all);

        $this->checkLinks($episode);

        return redirect('dashboard/series/'.$series->id.'/seasons/'.$season->id.'/episodes')->with(['note' => 'Successfully Updated Serie!', 'note_type' => 'success']);
    }

    public function destroy(Serie $series, Season $season, Episode $episode)
    {
        $episode->delete();

        return back()->with(['note' => 'Successfully Deleted Serie', 'note_type' => 'success']);
    }

    public function setSeasonEpisode()
    {
        $episodes = Episode::whereNull('season_episode')->take(5000)->get();

        foreach($episodes as $episode){
            if($episode->season->season_number < 10) {
                $season_number = '0'.$episode->season->season_number;
            } else {
                $season_number = $episode->season->season_number;
            }
            if($episode->episode_number < 10) {
                $episode_number = '0'.$episode->episode_number;
            } else {
                $episode_number = $episode->episode_number;
            }
            $episode->season_episode = 'S'.$season_number.'E'.$episode_number;
            $episode->save();
        }
    }

    public function updatePoster(Episode $episode, Request $request)
    {
        $all = $request->all();

        $month_year = date('FY').'/';
        $s3 = Storage::disk('s3');

        $s3->put($month_year.basename($all['image_link']), file_get_contents($all['image_link']));
        $all['poster'] = $month_year.basename($all['image_link']);

        file_put_contents($_SERVER['DOCUMENT_ROOT'].'/public/images/'.basename($all['image_link']), file_get_contents($all['image_link']));
        chmod($_SERVER['DOCUMENT_ROOT'].'/public/images/'.basename($all['image_link']), 0777);
        $filename = basename($all['image_link']);
        $small_filename = pathinfo($filename, PATHINFO_FILENAME) . '-small.' . pathinfo($filename, PATHINFO_EXTENSION);
        $img = Image::make(public_path('/public/images/'.$filename))->resize(320, null, function ($constraint) {
            $constraint->aspectRatio();
        })->save(public_path('/public/images/' . $small_filename));
        chmod(public_path('/public/images/' . $small_filename), 0777);
        $s3->put($month_year.$small_filename, file_get_contents(env('APP_URL').'/public/images/'.$small_filename));
        unlink(public_path('/public/images/' . $small_filename));
        unlink(public_path('/public/images/'.$filename));

        $episode->update($all);
        return response()->json(['result' => 'success']);
    }

    private function checkLinks($episode) 
    {
        if($episode->stream1 == '' || $episode->stream1 == null) {
            $episode->link_works = 0;
        } else {
            $file_headers = get_headers($episode->stream1);
            if($file_headers[0] == 'HTTP/1.1 404 Not Found') {
                $episode->link_works = 0;
            } else {
                $episode->link_works = 1;
            }
        }

        if($episode->full_subtitle_file_name == '' || $episode->full_subtitle_file_name == null) {
            $episode->link_subtitle_works = 0;   
        } else {
            $file_headers = get_headers($episode->full_subtitle_file_name);
            if($file_headers[0] == 'HTTP/1.1 404 Not Found') {
                $episode->link_subtitle_works = 0;
            } else {
                $episode->link_subtitle_works = 1;
            }
        }

        if($episode->full_subtitle_file_name_es == '' || $episode->full_subtitle_file_name_es == null) {
            $episode->link_subtitle_es_works = 0;
        } else {
            $file_headers = get_headers($episode->full_subtitle_file_name_es);
            if($file_headers[0] == 'HTTP/1.1 404 Not Found') {
                $episode->link_subtitle_es_works = 0;
            } else {
                $episode->link_subtitle_es_works = 1;
            }
        }
        
        $episode->save();
    }
}