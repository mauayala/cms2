<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class MediaController extends Controller {

	public function __construct()
    {
    	$this->middleware('auth');
    }

	public function index()
	{
		return view('media.index');
	}

	public function files(Request $request){
		$folder = $request->folder;
		if($folder == '/'){ $folder = ''; }
		$dir = "./uploads" . $folder;

		$response = $this->getFiles($dir);

		return response()->json([
			"name" => "files",
			"type" => "folder",
			"path" => $dir,
			"folder" => $folder,
			"items" => $response,
			"last_modified" => date('F jS, Y \a\t h:i:s A', filemtime($dir))
        ]);
	}

	private function getFiles($dir){
		$files = array();

		// Is there actually such a folder/file?

		if(file_exists($dir)){
			foreach(scandir($dir) as $f) {
				if(!$f || $f[0] == '.') {
					continue; // Ignore hidden files
				}
				if(is_dir($dir . '/' . $f)) {
					// The path is a folder
					$files[] = [
						"name" => $f,
						"type" => "folder",
						"path" => $dir . '/' . $f,
						"items" => $this->getNumberOfFilesInDir($dir . '/' . $f),
						"last_modified" => date('F jS, Y \a\t h:i:s A', filemtime($dir . '/' . $f))
					];
				} elseif( $this->is_image($dir . '/' . $f) ) {
					$files[] = [
						"name" => $f,
						"type" => "image",
						"path" => $dir . '/' . $f,
						"size" => filesize($dir . '/' . $f), // Gets the size of this file
						"last_modified" => date('F jS, Y \a\t h:i:s A', filemtime($dir . '/' . $f))
					];
				} elseif( strstr(mime_content_type($dir . '/' . $f), "video/") ) {
					$files[] = [
						"name" => $f,
						"type" => "video",
						"path" => $dir . '/' . $f,
						"size" => filesize($dir . '/' . $f), // Gets the size of this file
						"last_modified" => date('F jS, Y \a\t h:i:s A', filemtime($dir . '/' . $f))
					];
				} elseif( strstr(mime_content_type($dir . '/' . $f), "audio/") ) {
					$files[] = [
						"name" => $f,
						"type" => "audio",
						"path" => $dir . '/' . $f,
						"size" => filesize($dir . '/' . $f), // Gets the size of this file
						"last_modified" => date('F jS, Y \a\t h:i:s A', filemtime($dir . '/' . $f))
					];
				} else {
					// It is a file
					$files[] = [
						"name" => $f,
						"type" => "file",
						"path" => $dir . '/' . $f,
						"size" => filesize($dir . '/' . $f), // Gets the size of this file
						"last_modified" => date('F jS, Y \a\t h:i:s A', filemtime($dir . '/' . $f))
					];
				}
			}
		}
		return $files;
	}

	private function is_image($path)
	{
		$a = getimagesize($path);
		$image_type = $a[2];
		
		if(in_array($image_type , [IMAGETYPE_GIF , IMAGETYPE_JPEG ,IMAGETYPE_PNG , IMAGETYPE_BMP]))
			return true;
		return false;
	}

	private function getNumberOfFilesInDir($dir){
		$count = 0;
		if(file_exists($dir)){
			$files = scandir($dir);
			foreach($files as $file){
				if(!$file || $file[0] == '.') {
					continue; // Ignore hidden files
				} 
				$count += 1;
			}
		}
		return $count;
	}

	public function new_folder(Request $request){
		$new_folder = $request->new_folder;
		$success = false;
		$error = '';

		if(file_exists($new_folder)){
			$error = 'Sorry that folder already exists, please delete that folder if you wish to re-create it';
		} else {
			if(mkdir($new_folder)){
				$success = true;
			} else{
				$error = 'Sorry something seems to have gone wrong with creating the directory, please check your permissions';
			}
		}
		return ['success' => $success, 'error' => $error];
	}

	public function delete_file_folder(Request $request){
		$file_folder = $request->file_folder;
		$success = true;
		$error = '';

		if (is_dir($file_folder)) {
			if(!$this->rrmdir($file_folder)){
				$error = 'Sorry something seems to have gone wrong when deleting this folder, please check your permissions';
				$success = false;
			}
		} else {
			if(!unlink($file_folder)){
				$error = 'Sorry something seems to have gone wrong deleting this file, please check your permissions';
				$success = false;
			}
		}

		return ['success' => $success, 'error' => $error];
	}

	/********** recursively delete directory **********/
	private function rrmdir($dir) { 
        $deleted = true;
        if (is_dir($dir)) {
            $objects = scandir($dir);
            foreach ($objects as $object) {
                if ($object != "." && $object != "..") {
                    if (is_dir($dir."/".$object))
                        $this->rrmdir($dir."/".$object);
                    else
                        unlink($dir."/".$object);
                }
            }
            if(!rmdir($dir)){
                $deleted = false;
            }
        }
        return $deleted;
	 }


    public function get_all_dirs(){
        $base_dir = './uploads';
        $directories = $this->expandDirectories($base_dir);
        return response()->json($directories);
    }

    public function expandDirectories($base_dir) {

        $directories = [];
        foreach(scandir($base_dir) as $file) {
            if($file == '.' || $file == '..') continue;
            $dir = $base_dir.DIRECTORY_SEPARATOR.$file;
            if(is_dir($dir)) {
                $directories []= str_replace('./uploads/', '', $dir);
                $directories = array_merge($directories, $this->expandDirectories($dir));
            }
        }
        return $directories;
    }

	public function move_file(Request $request){
		$source = $request->source;
		$destination = $request->destination;
		$success = false;
		$error = '';

		if(!file_exists($destination)){
			if(rename($source, $destination)){
				$success = true;
			} else {
				$error = 'Sorry there seems to be a problem moving that file/folder, please make sure you have the correct permissions.';
			}
		} else {
			$error = 'Sorry there is already a file/folder with that existing name in that folder.';
		}

		return ['success' => $success, 'error' => $error];
	}

	public function upload(Request $request){
		try {
			if($request->hasFile('file')){
				$upload_path = $request->upload_path;
				$file = $request->file('file');
				$extension = $file->getClientOriginalExtension();
				$name = str_replace('.' . $extension, '-' . time() . '.' . $extension, $file->getClientOriginalName());

				$file->move($upload_path, $name);
				$success = true;
				$message = 'Successfully uploaded ' . $file->getClientOriginalName();
			} else {
				$success = false;
				$message = 'poop';
			}
		} catch(Exception $e){
			$success = false;
			$message = $e->getMessage();
		}

		return Response::json(['success' => $success, 'message' => $message]);
	}
}

