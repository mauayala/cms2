<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Libraries\ImageHandler;
use App\Models\Video;
use App\Models\SerieCategory;
use App\Models\VideoCategory;
use App\Models\Setting;
use Validator;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManagerStatic as Image;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

class VideoController extends Controller {
    public function index(Request $request)
    {
        if(isset($request->s)){
            $videos = VideoCategory::with(['videos' => function ($query) use ($request) {
                $query->where('title', 'LIKE', '%'.$request->s.'%')->orWhere('title_es', 'LIKE', '%'.$request->s.'%');
            }])->orderBy('order')->get();
        }elseif(isset($request->filter)){
            $videos = VideoCategory::with(['videos' => function ($query) use ($request) {
                $query->where('rating', $request->filter);
            }])->orderBy('order')->get();
        } else {
            $videos = VideoCategory::orderBy('order')->get();
        }        

        return view('videos.index', compact('videos'));
    }
    
    public function create()
    {
        $video_categories = VideoCategory::all();
        return view('videos.create', compact('video_categories'));
    }
    
    public function store(Request $request)
    {
        $data = $request->all();
        if($request->hls_link != ''){
            $data['video_file_name'] = '';
            $data['subtitle_file_name'] = '';
            $data['subtitle_file_name_es'] = '';
        }

        $month_year = date('FY').'/';
        $s3 = Storage::disk('s3');

        if (isset($data['image'])) {   
            $filename = basename($_FILES['image']['name']);
            $s3->put($month_year.$filename, file_get_contents($request->file('image')));
            $data['image'] = $month_year.$filename;
          
            $small_filename = pathinfo($filename, PATHINFO_FILENAME) . '-small.' . pathinfo($filename, PATHINFO_EXTENSION);
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

        if (isset($data['backdrop']) && !empty($data['backdrop'])) {
            $s3->put($month_year.basename($data['backdrop']), $data['backdrop']);
            $data['backdrop'] = $month_year.basename($data['backdrop']);
        } elseif (isset($data['backdrop_link']) && !empty($data['backdrop_link']) && $data['backdrop_link'] != 'https://image.tmdb.org/t/p/originalnull') {
            $s3->put($month_year.basename($data['backdrop_link']), file_get_contents($data['backdrop_link']));
            $data['backdrop'] = $month_year.basename($data['backdrop_link']);
        } else {
            $data['backdrop'] = 'placeholder.jpg';
        }

        if (isset($data['logo']) && !empty($data['logo'])) {
            $s3->put($month_year.basename($data['logo']), $data['logo']);
            $data['logo'] = $month_year.basename($data['logo']);
        }

        if(empty($data['active'])){
            $data['active'] = 0;
        }

        if(empty($all['en'])){
            $data['en'] = 0;
        }

        if(empty($all['es'])){
            $data['es'] = 0;
        }
        
        if(empty($data['featured'])){
            $data['featured'] = 0;
        }

        $data['imdb_rating'] = number_format($request->imdb_rating, 1);

        $video = Video::create($data);
        $this->checkLinks($video);

        if($video->link_works == 0 || $video->link_subtitle_works == 0 || $video->link_subtitle_es_works == 0) {
            $video->delete();

            return back()->with(['note' => 'Links does not work', 'note_type' => 'error']);
        }

        return redirect('dashboard/videos/create')->with(['note' => 'Pelicula agregada con exito!', 'note_type' => 'success']);
    }
    
    public function edit(Video $video)
    {
        $video_categories = VideoCategory::all();
        return view('videos.edit', compact('video', 'video_categories'));
    }
    
    public function update(Video $video, Request $request)
    {
        $all = $request->all();

        if($request->hls_link != ''){
            $all['video_file_name'] = '';
            $all['subtitle_file_name'] = '';
            $all['subtitle_file_name_es'] = '';
        }

        $month_year = date('FY').'/';
        $s3 = Storage::disk('s3');

        if (isset($all['image'])) {            
            $filename = basename($_FILES['image']['name']);            

            $s3->put($month_year.$filename, file_get_contents($request->file('image')));
            $all['image'] = $month_year.$filename;

            $small_filename = pathinfo($filename, PATHINFO_FILENAME) . '-small.' . pathinfo($filename, PATHINFO_EXTENSION);
            $img = Image::make($request->file('image')->getRealPath())->resize(320, null, function ($constraint) {
                $constraint->aspectRatio();
            })->save(public_path('/public/images/' . $small_filename));
            chmod(public_path('/public/images/' . $small_filename), 0777);
            $s3->put($month_year.$small_filename, file_get_contents(env('APP_URL').'/public/images/'.$small_filename));
            unlink(public_path('/public/images/' . $small_filename));
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
            chmod(public_path('/public/images/' . $small_filename), 0777);
            $s3->put($month_year.$small_filename, file_get_contents(env('APP_URL').'/public/images/'.$small_filename));
            unlink(public_path('/public/images/' . $small_filename));
            unlink(public_path('/public/images/'.$filename));
        } else {
            $all['image'] = $video->image;
        }

        if (isset($all['backdrop'])) {
            $s3->put($month_year.basename($_FILES['backdrop']['name']), file_get_contents($request->file('backdrop')));
            $all['backdrop'] = $month_year.basename($_FILES['backdrop']['name']);
        } elseif (isset($all['backdrop_link']) && !empty($all['backdrop_link'])) {
            $s3->put($month_year.basename($all['backdrop_link']), file_get_contents($all['backdrop_link']));
            $all['backdrop'] = $month_year.basename($all['backdrop_link']);
        } else {
            $all['backdrop'] = $video->backdrop;
        }

        if (isset($all['logo'])) {
            $s3->put($month_year.basename($_FILES['logo']['name']), file_get_contents($request->file('logo')));
            $all['logo'] = $month_year.basename($_FILES['logo']['name']);
        }

        if(empty($all['active'])){
            $all['active'] = 0;
        }

        if(empty($all['en'])){
            $all['en'] = 0;
        }

        if(empty($all['es'])){
            $all['es'] = 0;
        }

        if(empty($all['featured'])){
            $all['featured'] = 0;
        }

        $all['edited_by'] = \Auth::user()->id;

        $all['imdb_rating'] = number_format($request->imdb_rating, 1);

        $video->update($all);

        $this->checkLinks($video);

        return redirect('dashboard/videos')->with(['note' => 'Pelicula actualizada con exito!', 'note_type' => 'success']);
    }

    public function destroy(Video $video)
    {
        $this->deleteVideoImages($video);

        $video->delete();

        return redirect('dashboard/videos')->with(['note' => 'Pelicula borrada con exito', 'note_type' => 'success']);
    }

    private function deleteVideoImages($video){
        $ext = pathinfo($video->image, PATHINFO_EXTENSION);
        if(file_exists('/images/' . $video->image) && $video->image != 'placeholder.jpg'){
            @unlink('/images/' . $video->image);
        }

        if(file_exists('/images/' . str_replace('.' . $ext, '-large.' . $ext, $video->image) )  && $video->image != 'placeholder.jpg'){
            @unlink('/images/' . str_replace('.' . $ext, '-large.' . $ext, $video->image) );
        }

        if(file_exists('/images/' . str_replace('.' . $ext, '-medium.' . $ext, $video->image) )  && $video->image != 'placeholder.jpg'){
            @unlink('/images/' . str_replace('.' . $ext, '-medium.' . $ext, $video->image) );
        }

        if(file_exists('/images/' . str_replace('.' . $ext, '-small.' . $ext, $video->image) )  && $video->image != 'placeholder.jpg'){
            @unlink('/images/' . str_replace('.' . $ext, '-small.' . $ext, $video->image) );
        }
    }

    public function duplicate($title)
    {
        $video = Video::where('title', $title)->first();
        if($video){
            return ['result'=>1];
        }
        return ['result'=>0];
    }

    public function checkLink(Request $request)
    {
        $settings = Setting::first();
        $client = new Client();
        if($request->type == 'video_file_name' || $request->type == 'serie_file_name') {
            $full_link = $settings->server_link.$request->value;
        } elseif($request->type == 'subtitle_file_name' || $request->type == 'subtitle_file_name_es') {
            $full_link = $settings->subtitle_link.$request->value;
        }

        try {
            $file_headers = get_headers($full_link);
            if($file_headers[0] == 'HTTP/1.1 404 Not Found' || $file_headers[0] == 'HTTP/1.1 502 Bad Gateway') {
                $link_works = 0;
            } else {
                $link_works = 1;
            }
        } catch(\Exception $e) {
            $link_works = 0;
        }

        return $link_works;
    }

    private function checkLinks($video)
    {
        if($video->stream == '' || $video->stream == null) {
            $video->link_works = 0;
        } else {
            $file_headers = get_headers($video->stream);
            if($file_headers[0] == 'HTTP/1.1 404 Not Found' || $file_headers[0] == 'HTTP/1.1 502 Bad Gateway') {
                $video->link_works = 0;
            } else {
                $video->link_works = 1;
            }
        }

        if($video->full_subtitle_file_name == '' || $video->full_subtitle_file_name == null) {
            $video->link_subtitle_works = 0;
        } else {
            $file_headers = get_headers($video->full_subtitle_file_name);
            if($file_headers[0] == 'HTTP/1.1 404 Not Found' || $file_headers[0] == 'HTTP/1.1 502 Bad Gateway') {
                $video->link_subtitle_works = 0;
            } else {
                $video->link_subtitle_works = 1;
            }
        }

        if($video->full_subtitle_file_name_es == '' || $video->full_subtitle_file_name_es == null) {
            $video->link_subtitle_es_works = 0;
        } else {
            $file_headers = get_headers($video->full_subtitle_file_name_es);
            if($file_headers[0] == 'HTTP/1.1 404 Not Found' || $file_headers[0] == 'HTTP/1.1 502 Bad Gateway') {
                $video->link_subtitle_es_works = 0;
            } else {
                $video->link_subtitle_es_works = 1;
            }
        }
        $video->save();
    }

    public function pullTitleEs()
    {
        $videos = Video::whereNull('title_es')->get();
        $client = new \GuzzleHttp\Client;
        foreach($videos as $v) {
            $result = json_decode($client->get('https://api.themoviedb.org/3/search/movie?api_key=5fabc059c6c919ad8fa7014c1c844cf0&language=es-MX&query='.$v->title.'&year='.date('Y', strtotime($v->released_at)))->getBody());
            if(count($result->results) > 0 && $result->results[0]->title != $v->title) {
                $v->title_es = $result->results[0]->title;
                $v->save();
            }
        }
        echo 'success';
    }

    public function viewCount(Request $request)
    {
        $movies = new Video;

        if($request->has('from_date') && $request->from_date != '' && $request->has('to_date') && $request->to_date != '') {
            $movies = $movies->withCount(['video_views' => function($q) use($request) {
                $q->whereDate('created_at', '>', $request->from_date)->whereDate('created_at', '<', $request->to_date);
            }]);
        } elseif($request->has('from_date') && $request->from_date != '') {
            $movies = $movies->withCount(['video_views' => function($q) use($request) {
                $q->whereDate('created_at', '>', $request->from_date);
            }]);
        } elseif($request->has('to_date') && $request->to_date != '') {
            $movies = $movies->withCount(['video_views' => function($q) use($request) {
                $q->whereDate('created_at', '<', $request->to_date);
            }]);
        } else {
            $movies = $movies->withCount('video_views');
        }

        $movies = $movies->orderBy('video_views_count', 'desc')->get();

        return view('dashboard.movies_count', compact('movies'));
    }
}