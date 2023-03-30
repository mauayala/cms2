<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Serie;
use App\Models\Season;
use App\Libraries\ImageHandler;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManagerStatic as Image;

class SeasonController extends Controller {

    public function index(Serie $series, Request $request)
    {
        $seasons = Season::where('serie_id', $series->id)->orderBy('season_number')->get();

        return view('series.seasons.index', compact('seasons', 'series'));
    }

    public function create(Serie $series)
    {
        return view('series.seasons.create', compact('series'));
    }

    public function store(Serie $series, Request $request)
    {
        $data = $request->all();
        $data['serie_id'] = $series->id;

        $month_year = date('FY').'/';
        $s3 = Storage::disk('s3');

        if (isset($data['poster'])) {         
            $filename = basename($_FILES['poster']['name']);      
            $s3->put($month_year.$filename, file_get_contents($request->file('poster')));
                     
            $small_filename = pathinfo($filename, PATHINFO_FILENAME) . '-small.' . pathinfo($filename, PATHINFO_EXTENSION);
            $img = Image::make($request->file('poster')->getRealPath())->resize(320, null, function ($constraint) {
                $constraint->aspectRatio();
            })->save(public_path('/public/images/' . $small_filename));
            chmod(public_path('/public/images/' . $small_filename), 0777);
            $s3->put($month_year.$small_filename, file_get_contents(env('APP_URL').'/public/images/'.$small_filename));

            $data['poster'] = $month_year.$filename;
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

        $season = Season::create($data);
        if(isset($data['is_ajax'])){
            return $season;    
        } else {
            return redirect('dashboard/series/'.$series->id.'/seasons')->with(['note' => 'New Serie Successfully Added!', 'note_type' => 'success']);
        }        
    }

    public function edit(Serie $series, Season $season)
    {
        return view('series.seasons.edit', compact('series', 'season'));
    }

    public function update(Serie $series, Season $season, Request $request)
    {
        $all = $request->all();

        $month_year = date('FY').'/';
        $s3 = Storage::disk('s3');

        if (isset($all['poster'])) {            
            $all['poster'] = $month_year.basename($all['poster']);

            $filename = basename($_FILES['poster']['name']);            
            $small_filename = pathinfo($filename, PATHINFO_FILENAME) . '-small.' . pathinfo($filename, PATHINFO_EXTENSION);
            $img = Image::make($request->file('poster')->getRealPath())->resize(320, null, function ($constraint) {
                $constraint->aspectRatio();
            })->save(public_path('/public/images/' . $small_filename));
            chmod(public_path('/public/images/' . $small_filename), 0777);
            $s3->put($month_year.$small_filename, file_get_contents(env('APP_URL').'//images/'.$small_filename));
            $all['poster'] = $month_year.$small_filename;
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
            $s3->put($month_year.$small_filename, file_get_contents(env('APP_URL').'//images/'.$small_filename));
            unlink(public_path('/public/images/' . $small_filename));
            unlink(public_path('/public/images/'.$filename));
        } else {
            $all['poster'] = $season->poster;
        }
        
        $all['season_number'] = $season->season_number;
        $all['edited_by'] = \Auth::user()->id;
        $season->update($all);

        return redirect('dashboard/series/'.$serie->id.'/seasons/edit/' . $season->id)->with(['note' => 'Successfully Updated Serie!', 'note_type' => 'success']);
    }

    public function destroy(Serie $series, Season $season)
    {
        $season->delete();

        return redirect('dashboard/series')->with(['note' => 'Successfully Deleted Serie', 'note_type' => 'success']);
    }

    public function updatePoster(Season $season, Request $request)
    {
        $all = $request->all();

        $month_year = date('FY').'/';
        $s3 = Storage::disk('s3');

        $s3->put($month_year.basename($all['image_link']), file_get_contents($all['image_link']));
        $all['poster'] = $month_year.basename($all['image_link']);

        file_put_contents($_SERVER['DOCUMENT_ROOT'].'/public/images/'.basename($all['image_link']), file_get_contents($all['image_link']));
        $filename = basename($all['image_link']);
        $small_filename = pathinfo($filename, PATHINFO_FILENAME) . '-small.' . pathinfo($filename, PATHINFO_EXTENSION);
        $img = Image::make(public_path('/public/images/'.$filename))->resize(320, null, function ($constraint) {
            $constraint->aspectRatio();
        })->save(public_path('/public/images/' . $small_filename));
        $s3->put($month_year.$small_filename, file_get_contents(env('APP_URL').'//images/'.$small_filename));
        unlink(public_path('/public/images/' . $small_filename));
        unlink(public_path('/public/images/'.$filename));
        
        $all['edited_by'] = \Auth::user()->id;
        $season->update($all);

        return response()->json(['result' => 'success']);
    }
}