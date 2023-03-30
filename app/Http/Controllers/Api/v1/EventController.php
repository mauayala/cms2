<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use DB;
use App\Models\Event;

class EventController extends Controller {

	private $apikey = '5fabc059c6c919ad8fa7014c1c844cf0';

	public function index(Request $request)
	{
		$events = Event::with(['options' => function($q){
			$q->select('id', 'order', 'event_id', 'name', 'mbps', 'link', DB::raw('DATE_ADD(start_date, INTERVAL 5 HOUR) as start_date'), DB::raw('DATE_ADD(end_date, INTERVAL 5 HOUR) as end_date'), 'created_at', 'updated_at');
		}])->get();

		return response()->json(['events' => $events]);
	}

	public function show(Event $event)
	{
		return response()->json(['event' => $event]);
	}
}
