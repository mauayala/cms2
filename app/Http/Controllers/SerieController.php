<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Libraries\ImageHandler;
use App\Models\Serie;
use App\Models\SerieCategory;
use App\Models\Tag;
use Validator;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManagerStatic as Image;

class SerieController extends Controller {

    public function index(Request $request)
    {
        if(isset($request->s)){
            $series = SerieCategory::with(['series' => function ($query) use ($request) {
                $query->where('title', 'LIKE', '%'.$request->s.'%')->orWhere('title_es', 'LIKE', '%'.$request->s.'%');
            }])->orderBy('order')->get();
        }elseif(isset($request->filter)){
            $series = SerieCategory::with(['series' => function ($query) use ($request) {
                $query->where('rating', $request->filter);
            }])->orderBy('order')->get();
        } else {
            $series = SerieCategory::orderBy('order')->get();
        }           

        return view('series.index', compact('series'));
    }

    public function create()
    {
        $serie_categories = SerieCategory::all();
        return view('series.create', compact(
            'serie_categories'
        ));
    }

    public function store(Request $request)
    {
        if(!isset($request->backdrop) && ($request->backdrop_link == '' || $request->backdrop_link == 'https://image.tmdb.org/t/p/originalnull')) {
            return \Redirect::back()->with(['note' => 'Backdrop cannot be empty', 'note_type' => 'error']);
        }

        $data = $request->all();

        $month_year = date('FY').'/';
        $s3 = Storage::disk('s3');

        if (isset($data['image'])) {
            $filename = time().'.'.pathinfo(basename($_FILES['image']['name']), PATHINFO_EXTENSION);
            $s3->put($month_year.$filename, file_get_contents($request->file('image')));
                       
            $small_filename = pathinfo($filename, PATHINFO_FILENAME) . '-small.' . pathinfo($filename, PATHINFO_EXTENSION);
            $data['image'] = $month_year.$filename;
            $img = Image::make($request->file('image')->getRealPath())->resize(320, null, function ($constraint) {
                $constraint->aspectRatio();
            })->save(public_path('/public/images/' . $small_filename));
            chmod(public_path('/public/images/' . $small_filename), 0777);
            $s3->put($month_year.$small_filename, file_get_contents(env('APP_URL').'/public/images/'.$small_filename));
            unlink(public_path('/public/images/' . $small_filename));
        } elseif(isset($data['image_link']) && !empty($data['image_link'])) {
            $s3->put($month_year.basename($data['image_link']), file_get_contents($data['image_link']));
            $data['image'] = $month_year.basename($data['image_link']);

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
            $data['image'] = 'placeholder.jpg';
        }

        if (isset($data['backdrop'])) {
            $filename = time().'.'.pathinfo(basename($_FILES['backdrop']['name']), PATHINFO_EXTENSION);
            $data['backdrop'] = $month_year.$filename;
            $img = Image::make($request->file('backdrop')->getRealPath())->resize(320, null, function ($constraint) {
                $constraint->aspectRatio();
            })->save(public_path('/public/images/' . $filename));
            chmod(public_path('/public/images/' . $filename), 0777);
            $s3->put($month_year.$filename, file_get_contents(env('APP_URL').'/public/images/'.$filename));
            unlink(public_path('/public/images/'.$filename));
        } elseif (isset($data['backdrop_link']) && !empty($data['backdrop_link'])) {
            $s3->put($month_year.basename($data['backdrop_link']), file_get_contents($data['backdrop_link']));
            $data['backdrop'] = $month_year.basename($data['backdrop_link']);
        } else {
            $data['backdrop'] = 'placeholder.jpg';
        }

        if (isset($data['featured_backdrop'])) {
            $filename = time().'.'.pathinfo(basename($_FILES['featured_backdrop']['name']), PATHINFO_EXTENSION);
            $data['featured_backdrop'] = $month_year.$filename;
            $img = Image::make($request->file('featured_backdrop')->getRealPath())->resize(320, null, function ($constraint) {
                $constraint->aspectRatio();
            })->save(public_path('/public/images/' . $filename));
            chmod(public_path('/public/images/' . $filename), 0777);
            $s3->put($month_year.$filename, file_get_contents(env('APP_URL').'/public/images/'.$filename));
            unlink(public_path('/public/images/'.$filename));
        }
        
        if(empty($data['active'])){
            $data['active'] = 0;
        }

        if(empty($data['multiseasoned'])){
            $data['multiseasoned'] = 0;
        }

        if(empty($data['script_check'])){
            $data['script_check'] = 0;
        } else {
            $data['script_check'] = 1;
        }

        $data['imdb_rating'] = number_format($request->imdb_rating, 1);

        $serie = Serie::create($data);
        return redirect('dashboard/series')->with(['note' => 'New Serie Successfully Added!', 'note_type' => 'success']);
    }

    public function manualcreate()
    {
        $serie_categories = SerieCategory::all();
        return view('series.manualcreate', compact('serie_categories'));
    }

    public function edit(Serie $series)
    {
        $serie_categories = SerieCategory::all();

        return view('series.edit', compact('series', 'serie_categories'));
    }

    public function update(Serie $series, Request $request)
    {
        $all = $request->all();
        
        $month_year = date('FY').'/';
        $s3 = Storage::disk('s3');

        if (isset($all['image'])) {    
            $filename = basename($_FILES['image']['name']);      
            $s3->put($month_year.$filename, file_get_contents($request->file('image')));
                    
            $filename = basename($_FILES['image']['name']);            
            $small_filename = pathinfo($filename, PATHINFO_FILENAME) . '-small.' . pathinfo($filename, PATHINFO_EXTENSION);
            $all['image'] = $month_year.$filename;
            $img = Image::make($request->file('image')->getRealPath())->resize(320, null, function ($constraint) {
                $constraint->aspectRatio();
            })->save(public_path('/public/images/' . $small_filename));
            $s3->put($month_year.$small_filename, file_get_contents(env('APP_URL').'/public/images/'.$small_filename));
            unlink(public_path('/public/images/' . $small_filename));
            unlink(public_path('/public/images/'.$filename));
        } elseif(isset($all['image_link']) && !empty($all['image_link'])) {
            $s3->put($month_year.basename($all['image_link']), file_get_contents($all['image_link']));
            $all['image'] = $month_year.basename($all['image_link']);

            file_put_contents($_SERVER['DOCUMENT_ROOT'].'/public/images/'.basename($all['image_link']), file_get_contents($all['image_link']));
            chmod($_SERVER['DOCUMENT_ROOT'].'/public/images/'.basename($all['image_link']), 0777);
            $filename = basename($all['image_link']);
            $small_filename = pathinfo($filename, PATHINFO_FILENAME) . '-small.' . pathinfo($filename, PATHINFO_EXTENSION);
            $img = Image::make(public_path('/public/images/'.$filename))->resize(320, null, function ($constraint) {
                $constraint->aspectRatio();
            })->save(public_path('/public/images/' . $small_filename));
            $s3->put($month_year.$small_filename, file_get_contents(env('APP_URL').'/public/images/'.$small_filename));
            unlink(public_path('/public/images/' . $small_filename));
            unlink(public_path('/public/images/'.$filename));
        } else {
            $all['image'] = $series->image;
        }

        if (isset($all['backdrop'])) {
            $filename = basename($_FILES['backdrop']['name']);            
            //$small_filename = pathinfo($filename, PATHINFO_FILENAME) . '-small.' . pathinfo($filename, PATHINFO_EXTENSION);
            $all['backdrop'] = $month_year.$filename;
            $img = Image::make($request->file('backdrop')->getRealPath())->resize(320, null, function ($constraint) {
                $constraint->aspectRatio();
            })->save(public_path('/public/images/' . $filename));
            $s3->put($month_year.$filename, file_get_contents(env('APP_URL').'/public/images/'.$filename));
            unlink(public_path('/public/images/'.$filename));
        } elseif (isset($all['backdrop_link']) && !empty($all['backdrop_link'])) {
            $s3->put($month_year.basename($all['backdrop_link']), file_get_contents($all['backdrop_link']));
            $all['backdrop'] = $month_year.basename($all['backdrop_link']);
        } else {
            $all['backdrop'] = $series->backdrop;
        }

        if (isset($all['featured_backdrop'])) {
            $filename = time().'.'.pathinfo(basename($_FILES['featured_backdrop']['name']), PATHINFO_EXTENSION);
            //$small_filename = pathinfo($filename, PATHINFO_FILENAME) . '-small.' . pathinfo($filename, PATHINFO_EXTENSION);
            $all['featured_backdrop'] = $month_year.$filename;
            $img = Image::make($request->file('featured_backdrop')->getRealPath())->resize(320, null, function ($constraint) {
                $constraint->aspectRatio();
            })->save(public_path('/public/images/' . $filename));
            chmod(public_path('/public/images/' . $filename), 0777);
            $s3->put($month_year.$filename, file_get_contents(env('APP_URL').'/public/images/'.$filename));
            unlink(public_path('/public/images/'.$filename));
        }

        if(empty($all['active'])){
            $all['active'] = 0;
        }

        if(empty($data['multiseasoned'])){
            $data['multiseasoned'] = 0;
        }

        if(empty($data['script_check'])){
            $data['script_check'] = 0;
        } else {
            $data['script_check'] = 1;
        }

        $all['edited_by'] = \Auth::user()->id;

        $all['imdb_rating'] = number_format($request->imdb_rating, 1);

        $series->update($all);

        return redirect('dashboard/series/edit' . '/' . $series->id)->with(['note' => 'Successfully Updated Serie!', 'note_type' => 'success']);
    }

    public function destroy(Serie $serie)
    {
        $serie->delete();

        return redirect('dashboard/series')->with(['note' => 'Successfully Deleted Serie', 'note_type' => 'success']);
    }

    public function pullTitleEs()
    {
        $series = Serie::whereNull('title_es')->get();
        $client = new \GuzzleHttp\Client;
        foreach($series as $s) {
            $result = json_decode($client->get('https://api.themoviedb.org/3/search/tv?api_key=5fabc059c6c919ad8fa7014c1c844cf0&language=es-MX&query='.$s->title.'&first_air_date_year='.date('Y', strtotime($s->released_at)))->getBody());
            if(count($result->results) > 0) {
                $s->title_es = $result->results[0]->name;
                $s->save();
            }
        }
        echo 'success';
    }

    private function addUpdateSerieTags($serie, $tags){
        $tags = array_map('trim', explode(',', $tags));


        foreach($tags as $tag){
            
            $tag_id = $this->addTag($tag);
            $this->attachTagToSerie($serie, $tag_id);
        }  

        // Remove any tags that were removed from video
        foreach($serie->tags as $tag){
            if(!in_array($tag->name, $tags)){
                $this->detachTagFromSerie($serie, $tag->id);
                if(!$this->isTagContainedInAnySeries($tag->name)){
                    $tag->delete();
                }
            }
        }
    }

    /**************************************************
    /*
    /*  PRIVATE FUNCTION
    /*  addTag( tag_name )
    /*
    /*  ADD NEW TAG if Tag does not exist
    /*  returns tag id
    /*
    /**************************************************/

    private function addTag($tag){
        $tag_exists = Tag::where('name', $tag)->first();
            
        if($tag_exists){ 
            return $tag_exists->id; 
        } else {
            $new_tag = new Tag;
            $new_tag->name = strtolower($tag);
            $new_tag->save();
            return $new_tag->id;
        }
    }

    /**************************************************
    /*
    /*  PRIVATE FUNCTION
    /*  attachTagToVideo( video object, tag id )
    /*
    /*  Attach a Tag to a Video
    /*
    /**************************************************/

    private function attachTagToSerie($serie, $tag_id){
        // Add New Tags to video
        if (!$serie->tags->contains($tag_id)) {
            $serie->tags()->attach($tag_id);
        }
    }

    private function detachTagFromSerie($serie, $tag_id){
        // Detach the pivot table
        $serie->tags()->detach($tag_id);
    }

    public function isTagContainedInAnySeries($tag_name){
        // Check if a tag is associated with any videos
        $tag = Tag::where('name', $tag_name)->first();
        return (!empty($tag) && $tag->series->count() > 0) ? true : false;
    }

    public function duplicate($title)
    {
        $serie = Serie::where('title', $title)->first();
        if($serie){
            return ['result'=>1];
        }
        return ['result'=>0];
    }

    public function toggleSubtitleCheck(Serie $serie)
    {
        if($serie->script_check) {
            $serie->script_check = 0;
        } else {
            $serie->script_check = 1;
        }
        $serie->save();

        return response()->json('true');
    }

    public function viewCount(Request $request)
    {
        $series = new Serie;

        if($request->has('from_date') && $request->from_date != '' && $request->has('to_date') && $request->to_date != '') {
            $series = $series->withCount(['video_views' => function($q) use($request) {
                $q->whereDate('video_view.created_at', '>', $request->from_date)->whereDate('video_view.created_at', '<', $request->to_date);
            }]);
        } elseif($request->has('from_date') && $request->from_date != '') {
            $series = $series->withCount(['video_views' => function($q) use($request) {
                $q->whereDate('video_view.created_at', '>', $request->from_date);
            }]);
        } elseif($request->has('to_date') && $request->to_date != '') {
            $series = $series->withCount(['video_views' => function($q) use($request) {
                $q->whereDate('video_view.created_at', '<', $request->to_date);
            }]);
        } else {
            $series = $series->withCount('video_views');
        }

        $series = $series->orderBy('video_views_count', 'desc')->get();

        return view('dashboard.series_count', compact('series'));
    }
}