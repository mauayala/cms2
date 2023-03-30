<?php

namespace App\Http\Controllers\Api\v2;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AppVersion;

class AppVersionController extends Controller {

	public function index(Request $request)
	{
		return response()->json(['app_version' => AppVersion::orderBy('id', 'desc')->first()]);
	}
}