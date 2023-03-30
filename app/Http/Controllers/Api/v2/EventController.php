<?php

namespace App\Http\Controllers\Api\v2;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use App\Models\Event;

class EventController extends Controller {

	public function index(Request $request)
	{
		$events = Event::with(['options' => function($q){
			$q->select('id', 'order', 'event_id', 'name', 'mbps', 'link', \DB::raw('DATE_ADD(start_date, INTERVAL 5 HOUR) as start_date'), \DB::raw('DATE_ADD(end_date, INTERVAL 5 HOUR) as end_date'), 'created_at', 'updated_at');
		}])->get();

		return response()->json(['events' => $events]);
	}
}