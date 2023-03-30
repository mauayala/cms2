<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Libraries\ImageHandler;
use App\Models\Adultos;
use App\Models\AdultosCategory;
use App\Models\Setting;
use Validator;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManagerStatic as Image;

class AdultosController extends Controller {

    public function index(Request $request)
    {
        $adultos = AdultosCategory::orderBy('order')->get();

        return view('adultos.index', compact('adultos'));
    }

    public function create()
    {
        $adult_categories = AdultosCategory::all();
        $settings = Setting::first();
        return view('adultos.create', compact('adult_categories', 'settings'));
    }

    public function store(Request $request)
    {
        $month_year = date('FY').'/';
        $upload_folder = '/images/'.$month_year;
        $s3 = Storage::disk('s3');

        if (isset($data['image'])) {            
            $s3->put($month_year.basename($_FILES['image']['name']), file_get_contents($request->file('image')));
            $data['image'] = $month_year.basename($_FILES['image']['name']);

            $filename = basename($_FILES['image']['name']);            
            $small_filename = pathinfo($filename, PATHINFO_FILENAME) . '-small.' . pathinfo($filename, PATHINFO_EXTENSION);
            $img = Image::make($request->file('image')->getRealPath())->resize(320, null, function ($constraint) {
                $constraint->aspectRatio();
            })->save(public_path($upload_folder . $small_filename));
            $s3->put($month_year.$small_filename, file_get_contents(env('APP_URL').$upload_folder.$small_filename));
            $f = strval(str_replace("\0", "", $upload_folder.$filename));
            if(file_exists($f)){
                @unlink($upload_folder.$filename);
            }
        } else {
            $data['image'] = 'placeholder.jpg';
        }

        if (isset($data['backdrop'])) {
            $s3->put($month_year.basename($_FILES['backdrop']['name']), file_get_contents($request->file('backdrop')));
            $data['backdrop'] = $month_year.basename($_FILES['backdrop']['name']);
            //$data['backdrop'] = ImageHandler::uploadImage($data['backdrop'], 'images');
        } else {
            $data['backdrop'] = 'placeholder.jpg';
        }
        
        if(empty($data['active'])){
            $data['active'] = 0;
        }

        if(empty($data['featured'])){
            $data['featured'] = 0;
        }

        $video = Adultos::create($data);

        return redirect('dashboard/adultos')->with(['note' => 'New Video Successfully Added!', 'note_type' => 'success']);
    }

    public function edit(Adultos $adulto)
    {
        $adult_categories = AdultosCategory::all();
        $settings = Setting::first();
        return view('adultos.edit', compact('adulto', 'adult_categories', 'settings'));
    }

    public function update(Adultos $adulto, Request $request)
    {
        $all = $request->all();
        
        $month_year = date('FY').'/';
        $upload_folder = '/images/'.$month_year;
        $s3 = Storage::disk('s3');

        if (isset($all['image'])) {            
            $s3->put($month_year.basename($_FILES['image']['name']), file_get_contents($request->file('image')));
            $all['image'] = $month_year.basename($_FILES['image']['name']);

            $filename = basename($_FILES['image']['name']);            
            $small_filename = pathinfo($filename, PATHINFO_FILENAME) . '-small.' . pathinfo($filename, PATHINFO_EXTENSION);
            $img = Image::make($request->file('image')->getRealPath())->resize(320, null, function ($constraint) {
                $constraint->aspectRatio();
            })->save(public_path($upload_folder . $small_filename));
            $s3->put($month_year.$small_filename, file_get_contents(env('APP_URL').$upload_folder.$small_filename));
            $f = strval(str_replace("\0", "", $upload_folder.$filename));
            if(file_exists($f)){
                @unlink($upload_folder.$filename);
            }
        } else {
            $all['image'] = $adulto->image;
        }

        if (isset($all['backdrop'])) {
            $s3->put($month_year.basename($_FILES['backdrop']['name']), file_get_contents($request->file('backdrop')));
            $all['backdrop'] = $month_year.basename($_FILES['backdrop']['name']);
            //$data['backdrop'] = ImageHandler::uploadImage($data['backdrop'], 'images');
        } else {
            $all['backdrop'] = $adulto->backdrop;
        }

        if(empty($all['active'])){
            $all['active'] = 0;
        }

        if(empty($all['featured'])){
            $all['featured'] = 0;
        }

        $all['edited_by'] = \Auth::user()->id;

        $adulto->update($all);

        return redirect('dashboard/adultos')->with(['note' => 'Successfully Updated Video!', 'note_type' => 'success']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy(Adultos $adulto)
    {
        $this->deleteVideoImages($adulto);

        $adulto->delete();

        return redirect('/dashboard/adultos')->with(['note' => 'Successfully Deleted Video', 'note_type' => 'success']);
    }

    private function deleteVideoImages($adultos){
        $ext = pathinfo($adultos->image, PATHINFO_EXTENSION);
        if(file_exists('/images/' . $adultos->image) && $adultos->image != 'placeholder.jpg'){
            @unlink('/images/' . $adultos->image);
        }

        if(file_exists('/images/' . str_replace('.' . $ext, '-large.' . $ext, $adultos->image) )  && $adultos->image != 'placeholder.jpg'){
            @unlink('/images/' . str_replace('.' . $ext, '-large.' . $ext, $adultos->image) );
        }

        if(file_exists('/images/' . str_replace('.' . $ext, '-medium.' . $ext, $adultos->image) )  && $adultos->image != 'placeholder.jpg'){
            @unlink('/images/' . str_replace('.' . $ext, '-medium.' . $ext, $adultos->image) );
        }

        if(file_exists('/images/' . str_replace('.' . $ext, '-small.' . $ext, $adultos->image) )  && $adultos->image != 'placeholder.jpg'){
            @unlink('/images/' . str_replace('.' . $ext, '-small.' . $ext, $adultos->image) );
        }
    }

}