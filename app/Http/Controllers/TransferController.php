<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Credit;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use App\Exports\CreditExport;

class TransferController extends Controller {

	public function antifraud()
	{
		$users = User::whereIn('role', ['seller', 'distributor', 'admin', 'staff'])->get();

		return view('transfer.anti_fraud', compact('users'));
	}

	public function credit(Request $request)
	{
		$usercredits = DB::table('credits')->select(DB::raw('CASE from_user_id WHEN '.\Auth::user()->id.' THEN from_credit_amount ELSE to_credit_amount END as credit_amount'))
					->where('from_user_id', \Auth::user()->id)
					->orWhere('to_user_id', \Auth::user()->id)
					->orderBy('created_at', 'desc')
					->first();

		return view('transfer.credit', compact('usercredits'));
	}

	public function transfer(Request $request)
	{
		$touser = User::where('username', $request->user)->first();
		if(!$touser) {
			return back()->with(['note' => 'Choose valid user', 'note_type' => 'error']);
		}
		$fromuser = User::find(\Auth::user()->id);

		$tousercredits = DB::table('credits')->select(DB::raw('CASE from_user_id WHEN '.$touser->id.' THEN from_credit_amount ELSE to_credit_amount END as credit_amount'))
					->where('from_user_id', $touser->id)
					->orWhere('to_user_id', $touser->id)
					->orderBy('created_at', 'desc')
					->first();

		$fromusercredits = DB::table('credits')->select(DB::raw('CASE from_user_id WHEN '.\Auth::user()->id.' THEN from_credit_amount ELSE to_credit_amount END as credit_amount'))
					->where('from_user_id', \Auth::user()->id)
					->orWhere('to_user_id', \Auth::user()->id)
					->orderBy('created_at', 'desc')
					->first();

		if($fromusercredits->credit_amount >= $request->amount && $request->amount > 0){
			$credit = new Credit;
			$credit->from_user_id = \Auth::user()->id;
			$credit->to_user_id = $touser->id;
			$credit->amount = $request->amount;
			$credit->to_credit_amount = (($tousercredits) ? $tousercredits->credit_amount : 0) + $request->amount;
			$credit->from_credit_amount = $fromusercredits->credit_amount - $request->amount;
			if($credit->save()){
				if($touser->role == 'customer'){
					if(strtotime($touser->trial_ends_at) >= time()){
						$touser->trial_ends_at = date('Y-m-d '.date('H:i:s', strtotime($touser->trial_ends_at)), strtotime("+$request->amount months", strtotime($touser->trial_ends_at)));
					} else {
						$touser->trial_ends_at = date('Y-m-d H:i:s', strtotime("+$request->amount months", time()));
					}
				}
				$touser->save();
			}
			return redirect('dashboard/transfer/log');
		}
		return back()->with(['note' => 'You do not have enough credit to transfer', 'note_type' => 'error']);
	}

	public function log(Request $request)
	{
		if(isset($request->user) && isset($request->activity)){
			$user = User::find($request->user);
			if($request->activity == 'all'){
				$logs = Credit::whereMonth('created_at', $request->month)
					->whereYear('created_at', $request->year)
					->where(function($query) use($user) {
						$query->where('from_user_id', $user->id)->orWhere('to_user_id', $user->id);
					})->orderBy('created_at', 'desc')->get();
			} elseif($request->activity == 'transfer'){
				$logs = Credit::join('users as u1', 'u1.id', '=', 'credits.from_user_id')
					->join('users as u2', 'u2.id', '=', 'credits.to_user_id')
					->whereMonth('credits.created_at', $request->month)
					->whereYear('credits.created_at', $request->year)
					->where(function($query) use($user) {
						$query->where('credits.from_user_id', $user->id)->orWhere('credits.to_user_id', $user->id);
					})
					->where(function($query) {
						$query->where('u1.role', '<>', 'customer')->where('u1.role', '<>', 'owner')->where('u2.role', '<>', 'customer')->where('u2.role', '<>', 'owner');
					})
					->orderBy('credits.created_at', 'desc')->get();
			} elseif($request->activity == 'owner'){
				$logs = Credit::join('users as u1', 'u1.id', '=', 'credits.from_user_id')
					->join('users as u2', 'u2.id', '=', 'credits.to_user_id')
					->whereMonth('credits.created_at', $request->month)
					->whereYear('credits.created_at', $request->year)
					->where(function($query) use($user) {
						$query->where('credits.from_user_id', $user->id)->orWhere('credits.to_user_id', $user->id);
					})
					->where(function($query) {
						$query->where('u1.role', 'owner')->where('u2.role', '<>', 'customer');
					})
					->orderBy('credits.created_at', 'desc')->get([DB::raw('*'), 'credits.created_at']);
			} elseif($request->activity == 'customer'){
				$logs = Credit::whereMonth('created_at', $request->month)
					->whereYear('created_at', $request->year)
					->where(function($query) use($user) {
						$query->where('from_user_id', $user->id)->orWhere('to_user_id', $user->id);
					})
					->whereHas('touser', function($query) {
						$query->where('role', 'customer');
					})
					->orderBy('created_at', 'desc')->get();
			}
		} elseif(isset($request->activity)){
			$user = User::find(\Auth::user()->id);
			if($request->activity == 'all'){
				$logs = Credit::whereMonth('created_at', $request->month)
					->whereYear('created_at', $request->year)
					->where(function($query) use($user) {
						$query->where('from_user_id', $user->id)->orWhere('to_user_id', $user->id);
					})->orderBy('created_at', 'desc')->get();
			} elseif($request->activity == 'transfer'){
				$logs = Credit::join('users as u1', 'u1.id', '=', 'credits.from_user_id')
					->join('users as u2', 'u2.id', '=', 'credits.to_user_id')
					->whereMonth('credits.created_at', $request->month)
					->whereYear('credits.created_at', $request->year)
					->where(function($query) use($user) {
						$query->where('credits.from_user_id', $user->id)->orWhere('credits.to_user_id', $user->id);
					})
					->where(function($query) {
						$query->where('u1.role', '<>', 'customer')->where('u1.role', '<>', 'owner')->where('u2.role', '<>', 'customer')->where('u2.role', '<>', 'owner');
					})
					->orderBy('credits.created_at', 'desc')->get();
			} elseif($request->activity == 'owner'){
				$logs = Credit::join('users as u1', 'u1.id', '=', 'credits.from_user_id')
					->whereMonth('credits.created_at', $request->month)
					->whereYear('credits.created_at', $request->year)
					->where(function($query) use($user) {
						$query->where('credits.from_user_id', $user->id)->orWhere('credits.to_user_id', $user->id);
					})
					->where(function($query) {
						$query->where('u1.role', 'owner');
					})
					->orderBy('credits.created_at', 'desc')->get();
			} elseif($request->activity == 'customer'){
				$logs = Credit::join('users as u2', 'u2.id', '=', 'credits.to_user_id')
					->whereMonth('credits.created_at', $request->month)
					->whereYear('credits.created_at', $request->year)
					->where(function($query) use($user) {
						$query->where('credits.from_user_id', $user->id)->orWhere('credits.to_user_id', $user->id);
					})
					->where(function($query) {
						$query->where('u2.role', 'customer');
					})
					->orderBy('credits.created_at', 'desc')->get();
			}
		} else {
			$user = User::find(\Auth::user()->id);
			$logs = Credit::whereMonth('created_at', date('m'))
					->whereYear('created_at', date('Y'))
					->where(function($query) use($user) {
						$query->where('from_user_id', $user->id)->orWhere('to_user_id', $user->id);
					})->orderBy('created_at', 'desc')->get();
		}
		if(\Auth::user()->role == 'owner'){
			$rawusers = User::orderBy('username')->get();
			$users = ['owner' => [], 'admin' => [], 'distributor' => [], 'seller' => [], 'customer' => []];
			foreach ($rawusers as $u) {
				if($u->id != \Auth::user()->id)
					$users[$u->role][] = $u;
			}
			if(count($users['owner']) < 1){
				unset($users['owner']);
			}
			if(count($users['admin']) < 1){
				unset($users['admin']);
			}
			if(count($users['distributor']) < 1){
				unset($users['distributor']);
			}
			if(count($users['seller']) < 1){
				unset($users['seller']);
			}
			if(count($users['customer']) < 1){
				unset($users['customer']);
			}
			return view('transfer.log', compact('logs', 'users', 'user'));
		}
		return view('transfer.log', compact('logs', 'user'));
	}

	public function mylog(Request $request)
	{
		if(isset($request->user) && isset($request->activity)){
			$user = User::find($request->user);
			if($request->activity == 'all'){
				$logs = Credit::whereMonth('created_at', $request->month)
					->whereYear('created_at', $request->year)
					->where(function($query) use($user) {
						$query->where('from_user_id', $user->id)->orWhere('to_user_id', $user->id);
					})->orderBy('created_at', 'desc')->get();
			} elseif($request->activity == 'transfer'){
				$logs = Credit::join('users as u1', 'u1.id', '=', 'credits.from_user_id')
					->join('users as u2', 'u2.id', '=', 'credits.to_user_id')
					->whereMonth('credits.created_at', $request->month)
					->whereYear('credits.created_at', $request->year)
					->where(function($query) use($user) {
						$query->where('credits.from_user_id', $user->id)->orWhere('credits.to_user_id', $user->id);
					})
					->where(function($query) {
						$query->where('u1.role', '<>', 'customer')->where('u1.role', '<>', 'owner')->where('u2.role', '<>', 'customer')->where('u2.role', '<>', 'owner');
					})
					->orderBy('credits.created_at', 'desc')->get();
			} elseif($request->activity == 'owner'){
				$logs = Credit::join('users as u1', 'u1.id', '=', 'credits.from_user_id')
					->whereMonth('credits.created_at', $request->month)
					->whereYear('credits.created_at', $request->year)
					->where(function($query) use($user) {
						$query->where('credits.from_user_id', $user->id)->orWhere('credits.to_user_id', $user->id);
					})
					->where(function($query) {
						$query->where('u1.role', 'owner');
					})
					->orderBy('credits.created_at', 'desc')->get();
			} elseif($request->activity == 'customer'){
				$logs = Credit::join('users as u2', 'u2.id', '=', 'credits.to_user_id')
					->whereMonth('credits.created_at', $request->month)
					->whereYear('credits.created_at', $request->year)
					->where(function($query) use($user) {
						$query->where('credits.from_user_id', $user->id)->orWhere('credits.to_user_id', $user->id);
					})
					->where(function($query) {
						$query->where('u2.role', 'customer');
					})
					->orderBy('credits.created_at', 'desc')->get();
			}
		} elseif(isset($request->activity)){
			$user = User::find(\Auth::user()->id);
			if($request->activity == 'all'){
				$logs = Credit::whereMonth('created_at', $request->month)
					->whereYear('created_at', $request->year)
					->where(function($query) use($user) {
						$query->where('from_user_id', $user->id)->orWhere('to_user_id', $user->id);
					})->orderBy('created_at', 'desc')->get();
			} elseif($request->activity == 'transfer'){
				$logs = Credit::join('users as u1', 'u1.id', '=', 'credits.from_user_id')
					->join('users as u2', 'u2.id', '=', 'credits.to_user_id')
					->whereMonth('credits.created_at', $request->month)
					->whereYear('credits.created_at', $request->year)
					->where(function($query) use($user) {
						$query->where('credits.from_user_id', $user->id)->orWhere('credits.to_user_id', $user->id);
					})
					->where(function($query) {
						$query->where('u1.role', '<>', 'customer')->where('u1.role', '<>', 'owner')->where('u2.role', '<>', 'customer')->where('u2.role', '<>', 'owner');
					})
					->orderBy('credits.created_at', 'desc')->get();
			} elseif($request->activity == 'owner'){
				$logs = Credit::join('users as u1', 'u1.id', '=', 'credits.from_user_id')
					->whereMonth('credits.created_at', $request->month)
					->whereYear('credits.created_at', $request->year)
					->where(function($query) use($user) {
						$query->where('credits.from_user_id', $user->id)->orWhere('credits.to_user_id', $user->id);
					})
					->where(function($query) {
						$query->where('u1.role', 'owner');
					})
					->orderBy('credits.created_at', 'desc')->get();
			} elseif($request->activity == 'customer'){
				$logs = Credit::join('users as u2', 'u2.id', '=', 'credits.to_user_id')
					->whereMonth('credits.created_at', $request->month)
					->whereYear('credits.created_at', $request->year)
					->where(function($query) use($user) {
						$query->where('credits.from_user_id', $user->id)->orWhere('credits.to_user_id', $user->id);
					})
					->where(function($query) {
						$query->where('u2.role', 'customer');
					})
					->orderBy('credits.created_at', 'desc')->get();
			}
		} else {
			$user = User::find(\Auth::user()->id);
			$logs = Credit::whereMonth('created_at', date('m'))
					->whereYear('created_at', date('Y'))
					->where(function($query) use($user) {
						$query->where('from_user_id', $user->id)->orWhere('to_user_id', $user->id);
					})->orderBy('created_at', 'desc')->get();
		}
		if(\Auth::user()->role == 'owner'){
			$rawusers = User::orderBy('username')->get();
			$users = ['owner' => [], 'admin' => [], 'distributor' => [], 'seller' => [], 'customer' => []];
			foreach ($rawusers as $u) {
				if($u->id != \Auth::user()->id)
					$users[$u->role][] = $u;
			}
			if(count($users['owner']) < 1){
				unset($users['owner']);
			}
			if(count($users['admin']) < 1){
				unset($users['admin']);
			}
			if(count($users['distributor']) < 1){
				unset($users['distributor']);
			}
			if(count($users['seller']) < 1){
				unset($users['seller']);
			}
			if(count($users['customer']) < 1){
				unset($users['customer']);
			}
			return view('transfer.mylog', compact('logs', 'users', 'user'));
		}
		return view('transfer.mylog', compact('logs', 'user'));
	}

	public function create(Request $request)
	{
		return view('transfer.create');
	}

	public function getUsers(Request $request)
	{
		if(\Auth::user()->role == 'owner') {
			return response()->json(User::select('id', \DB::raw('username as value'), \DB::raw('username as label'))->where('username', 'like', '%'.$request->term.'%')->orderBy('username')->get());
		} else {
			return response()->json(User::select('id', \DB::raw('username as value'), \DB::raw('username as label'))->where('created_by', \Auth::user()->id)->where('username', 'like', '%'.$request->term.'%')->orderBy('username')->get());
		}
	}

	public function store(Request $request)
	{
		$admin_user = \Auth::user();
		$allowed_roles = ["owner"];
		if (!in_array($admin_user->role, $allowed_roles)) {
			return back()->with(['note' => 'User permission denied. Please try again.', 'note_type' => 'error']);
		}

		$user = User::where('username', $request->user)->first();
		if(!$user) {
			return back()->with(['note' => 'Choose valid user', 'note_type' => 'error']);
		}
		if($user->role == 'customer'){
			if(strtotime($user->trial_ends_at) >= time()){
				$user->trial_ends_at = date('Y-m-d '.date('H:i:s', strtotime($user->trial_ends_at)), strtotime("+$request->amount months", strtotime($user->trial_ends_at)));
			} else {
				$user->trial_ends_at = date('Y-m-d H:i:s', strtotime("+$request->amount months", time()));
			}
		}
		$user->save();

		$touser = DB::table('credits')->select(DB::raw('CASE from_user_id WHEN '.$user->id.' THEN from_credit_amount ELSE to_credit_amount END as credit_amount'))
					->where('from_user_id', $user->id)
					->orWhere('to_user_id', $user->id)
					->orderBy('created_at', 'desc')
					->first();

		$credit = new Credit;
		$credit->from_user_id = \Auth::user()->id;
		$credit->to_user_id = $user->id;
		$credit->amount = $request->amount;
		$credit->to_credit_amount = (($touser) ? $touser->credit_amount : 0) + $request->amount;
		$credit->save();
		return redirect('dashboard/transfer/create');
	}

	public function export(Request $request)
	{
		$export = new CreditExport($request->activity, $request->user, $request->month, $request->year);
		return \Excel::download($export, 'transfer_history.xlsx');


		\Excel::create('Transfer history', function($excel) use($logs) {
			$excel->sheet('Sheetname', function($sheet) use($logs) {
				$sheet->fromModel($logs);
			});
		})->download('xlsx');
	}
}