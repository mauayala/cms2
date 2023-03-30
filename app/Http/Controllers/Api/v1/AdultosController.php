<?php 

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use App\Models\Adultos;
use App\Models\User;
use App\Models\AdultosCategory;
use App\Models\Setting;

class AdultosController extends Controller {

	public function index(Request $request)
	{
		$user = User::where('access_token', $request->token)->first();

		$adultos = Adultos::where('active', 1)->get();
		$settings = Setting::first();
		$adultos_categories = AdultosCategory::with('adultos')->orderBy('order')->get();

		for($i = 0; $i < count($adultos_categories); $i++){
			for ($j=0; $j < count($adultos_categories[$i]->adultos); $j++) {
				$adultos_categories[$i]->adultos[$j]['category_name'] = $adultos_categories[$i]->name;
				$adultos_categories[$i]->adultos[$j]->image = $adultos_categories[$i]->adultos[$j]->full_image;
				$adultos_categories[$i]->adultos[$j]->backdrop = $adultos_categories[$i]->adultos[$j]->full_backdrop;
				$adultos_categories[$i]->adultos[$j]->runtime = intval($adultos_categories[$i]->adultos[$j]->runtime) * 60;

				$adultos_categories[$i]->adultos[$j]->stream = $settings->server_link.$adultos_categories[$i]->adultos[$j]->video_file_name;
				$adultos_categories[$i]->adultos[$j]->streamFormat = substr($adultos_categories[$i]->adultos[$j]->video_file_name, strrpos($adultos_categories[$i]->adultos[$j]->video_file_name, '.') + 1);
				unset($adultos_categories[$i]->adultos[$j]->video_file_name);
				$adultos_categories[$i]->adultos[$j]->subtitle_file_name = $settings->server_link.$adultos_categories[$i]->adultos[$j]->subtitle_file_name;
				$adultos_categories[$i]->adultos[$j]->subtitle_file_name_es = $settings->server_link.$adultos_categories[$i]->adultos[$j]->subtitle_file_name_es;
			}
		}

		return response()->json($adultos_categories);
	}

	public function adult(Adultos $adultos, Request $request)
	{ 
		$user = User::where('access_token', $request->token)->first();

		$adultos->image = $adultos->full_image;
		$adultos->backdrop = $adultos->full_backdrop;
		return response()->json($adultos);
	}

	public function adultos_categories(Request $request){
		$adultos_categories = AdultosCategory::where('parent_id', $request->topcategory)->orderBy('order')->get();
		return response()->json($adultos_categories);
	}

	public function adultos_category($id){
		$adultos_category = AdultosCategory::find($id);
		return response()->json($adultos_category);
	}

}
