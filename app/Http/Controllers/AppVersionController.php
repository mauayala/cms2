<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AppVersion;
use Illuminate\Support\Facades\Storage;

class AppVersionController extends Controller {

    public function index(){
        $app_versions = AppVersion::orderBy('id', 'desc')->get();

        return view('app_versions.index', compact('app_versions'));
    }

    public function store(Request $request){
        $s3 = Storage::disk('s3');
        $filename = basename($_FILES['apk']['name']);      
        $s3->put('app_versions/cloudtv'.str_replace('.','',$request->version).'.'.$request->apk->extension(), file_get_contents($request->file('apk')));
        $data['version'] = $request->version;
        $data['link'] = 'app_versions/cloudtv'.str_replace('.','',$request->version).'.'.$request->apk->extension();
        $app_version = AppVersion::create($data);
        return redirect()->route('dashboard.app_versions.index')->with(['note' => 'Categoria agregada con exito.', 'note_type' => 'success']);
    }
}
