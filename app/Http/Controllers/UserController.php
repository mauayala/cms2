<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Country;
use App\Models\Credit;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Pagination\LengthAwarePaginator;
use Excel;

class UserController extends Controller{

	public function index(Request $request)
	{
		$expired_users5 = User::where('role', 'customer');
		$online_users = User::where('role', 'customer');
		$active_users = User::where('role', 'customer');
		$expired_users = User::where('role', 'customer');
		if($request->search != ''){
			$expired_users5 = $expired_users5->where('username', 'LIKE', '%'.$request->search.'%');
			$online_users = $online_users->where('username', 'LIKE', '%'.$request->search.'%');
			$active_users = $active_users->where('username', 'LIKE', '%'.$request->search.'%');
			$expired_users = $expired_users->where('username', 'LIKE', '%'.$request->search.'%');
		}
		if(\Auth::user()->role != 'owner') {
			$expired_users5 = $expired_users5->where('created_by', \Auth::user()->id);
			$online_users = $online_users->where('created_by', \Auth::user()->id);
			$active_users = $active_users->where('created_by', \Auth::user()->id);
			$expired_users = $expired_users->where('created_by', \Auth::user()->id);
		}
		$expired_users5 = $expired_users5->where('trial_ends_at', '>=', date('Y-m-d 00:00:00', time() - 86400*5))->where('trial_ends_at', '<', date('Y-m-d H:i:s'))->orderBy('trial_ends_at')->get();
		$online_users = $online_users->where('last_action_at', '>', date('Y-m-d H:i:s', time()-7200))->orderBy('last_action_at')->get();
		$active_users = $active_users->where('trial_ends_at', '>', date('Y-m-d H:i:s'))->orderBy('trial_ends_at')->get();
		$expired_users = $expired_users->where('trial_ends_at', '<', date('Y-m-d 00:00:00', time() - 86400*5))->orderBy('trial_ends_at', 'desc')->get();

		$active_customers = $active_users->union($expired_users5)->union($online_users)->union($expired_users);

		$page = $request->page ?? 1;
		$customers = new LengthAwarePaginator(array_slice($active_customers->toArray(), 50*($page - 1), 50), count($active_customers), 50, $page, ['path' => '/dashboard/users']);
		
		$total_customers = User::where('active', 1)
			->where('role', 'customer')
			->where('trial_ends_at', '>=', date('Y-m-d H:i:s'))
			->where('created_by', \Auth::user()->id)
			->count();

		$new_customers = User::where('active', 1)
			->where('role', 'customer')
			->where('trial_ends_at', '>=', date('Y-m-d H:i:s'))
			->whereMonth('created_at', date('m'))
			->whereYear('created_at', date('Y'))
			->where('created_by', \Auth::user()->id)
			->count();

		$online_customers = User::where('last_action_at', '>', date('Y-m-d H:i:s', time() - 7200))
			->where('role', 'customer')
			->where('created_by', \Auth::user()->id)
			->count();
				
		return view('users.index', compact('customers', 'total_customers', 'new_customers', 'online_customers'));
	}

	public function updateUser(){
		if(isset($request->s)){
			$search_value = $request->s;
		} else {
			$search_value = '';
		}

		if($search_value != ''){
			$users = User::where('username', 'LIKE', '%'.$search_value.'%')->orderBy('trial_ends_at')->get();
		} elseif (\Auth::user()->role == 'owner') {
			$users = User::orderBy('trial_ends_at')->get();
		} else {
			$users = User::where('created_by', \Auth::user()->id)->orderBy('trial_ends_at')->get();
		}

		$rawusers = $users;

		$owner = $staff = $admin = $distributor = $seller = $customer = [];
		$users = $users->toArray();
		foreach ($users as $user) {
			if($user['role'] == 'owner'){
				$owner[] = $user;
			} elseif($user['role'] == 'staff'){
				$staff[] = $user;
			} elseif($user['role'] == 'admin'){
				$admin[] = $user;
			} elseif($user['role'] == 'distributor'){
				$distributor[] = $user;
			} elseif($user['role'] == 'seller'){
				$seller[] = $user;
			} elseif($user['role'] == 'customer'){
				$customer[] = $user;
			}
		}
		usort($customer, function ($a, $b) {
			if($a['trial_ends_at'] == $b['trial_ends_at']){
				return 0;
			}
			return ($a['trial_ends_at'] < $b['trial_ends_at']) ? -1 : 1;
		});

		$users = array_merge($owner, $staff, $admin, $distributor, $seller, $customer);

		return view('users.updateUser', compact('users', 'rawusers'));
	}

	public function unlink(User $user, Request $request){
		$user->mac = null;
		if($request->device == 1) {
			$user->devicenumber = null;
			$user->model = null;
		} elseif($request->device == 2) {
			$user->devicenumber2 = null;
			$user->model2 = null;
		}
		
		$user->save();
		return back()->with('success', 'Successfully unlinked');
	}

	public function create(){
		$countries = Country::orderBy('country')->get();
		return view('users.create', compact('countries'));
	}

	public function store(Request $request){
		$found = User::where('username', $request->username)->first();
		if($found){
			return back()->with(['note' => 'This username already exists. Please try again.', 'note_type' => 'error']);
		}
		$user = [];
		$admin_user = \Auth::user();
		if (empty($admin_user)) {
			return back()->with(['note' => 'Not authenticated. Action aborted.', 'note_type' => 'error']);
		}
		switch ($admin_user->role) {
			case "owner":
				$allowed_roles = array("staff", "admin", "distributor", "seller", "customer");
				break;
			case "staff":
				$allowed_roles = array("seller", "customer");
				break;
			case "admin":
				$allowed_roles = array("distributor", "seller", "customer");
				break;
			case "distributor":
				$allowed_roles = array("seller", "customer");
				break;
			case "seller":
				$allowed_roles = array("customer");
				break;
			default:
				$allowed_roles = array();
				break;
		}

		if (!in_array($request->role, $allowed_roles)) {
			return back()->with(['note' => 'User permission denied. Please try again.', 'note_type' => 'error']);
		}
		if($request->role == 'customer') {
			$user['firstname'] = trim($request->firstname);
			$user['lastname'] = trim($request->lastname);
			$user['password'] = Hash::make($request->password);
			$user['username'] = trim($request->username);
			$user['countrylock'] = $request->countrylock;
			$user['role'] = $request->role;

			$user['created_by'] = \Auth::user()->id;
			$user['pin'] = $request->pin;
			$user['trial_ends_at'] = date('Y-m-d h:i:s', time());
			User::create($user);
		} else {
		    $user['firstname'] = trim($request->firstname);
		    $user['lastname'] = trim($request->lastname);
		    $user['password'] = Hash::make($request->password);
		    $user['username'] = trim($request->username);
		    $user['countrylock'] = $request->countrylock;
		    $user['role'] = $request->role;

		    $user['created_by'] = \Auth::user()->id;
		    $user['pin'] = $request->pin;
		    $user['trial_ends_at'] = date('Y-m-d h:i:s', time());
		    User::create($user);
		}

		return \Redirect::to('dashboard/users')->with(['note' => 'Successfully Created New User', 'note_type' => 'success']);
	}

	public function edit(User $user){
		$users = User::where('created_by', \Auth::user()->id)->where('id', $user->id)->count();

		if($users == 1 || \Auth::user()->role == 'owner'){
			$countries = Country::orderBy('country')->get();
			return view('users.edit', compact('user', 'countries'));
		} else {
			return \Redirect::to('dashboard/users')->with(['note' => 'You do not have access to edit this user', 'note_type' => 'error']);
		}
	}

	public function update(Request $request, User $user){
		$user->firstname = trim($request->firstname);
		$user->lastname = trim($request->lastname);
		//$user->active = $request->active;
		$user->username = trim($request->username);
		if(\Auth::user()->role == 'owner'){
			$user->role = $request->role;
		}
		$user->pin = $request->pin;

		if($request->password != ''){
			$user->password = Hash::make($request->password);
		}

		$user->save();
		return redirect('dashboard/users/' . $user->id.'/edit')->with(['note' => 'Successfully Updated User Settings', 'note_type' => 'success']);
	}

	public function destroy($id)
	{
		User::destroy($id);
		return \Redirect::to('dashboard/users')->with(['note' => 'Successfully Deleted User', 'note_type' => 'success']);
	}

	public function request()
	{
		return view('users.request');
	}

	public function sendRequest(Request $request)
	{
		$user = User::where('username', $request->username)->whereNull('created_by')->first();
		if(isset($user->id)){
			$user->created_by = \Auth::user()->id;
			$user->save();
			return redirect('/dashboard/users')->with(['note' => 'User is imported to your list of customers', 'note_type' => 'success']);
		} else {
			return back()->with(['note' => 'Username is not found', 'note_type' => 'error']);
		}
	}

	public function user($username, Request $request)
	{
		$user = User::where('username', $username)->first();
		if (\Auth::user()->role == 'owner') {
			$staff = User::where('created_by', $user->id)->where('role', 'staff')->get();
			$admin = User::where('created_by', $user->id)->where('role', 'admin')->get();
			$distributor = User::where('created_by', $user->id)->where('role', 'distributor')->get();
			$seller = User::where('created_by', $user->id)->where('role', 'seller')->get();
			$customer = User::where('created_by', $user->id)->where('role', 'customer')->orderBy('trial_ends_at')->get();
		} else {
			$staff = $admin = collect();
			$distributor = User::where('created_by', $user->id)->where('role', 'distributor')->get();
			$seller = User::where('created_by', $user->id)->where('role', 'seller')->get();
			$customer = User::where('created_by', $user->id)->where('role', 'customer')->orderBy('trial_ends_at')->get();
		}

		$rawusers = $customer;

		$users = $staff->merge($admin)->merge($distributor)->merge($seller)->merge($customer);

		$page = null;
		$page = $page ?: (\Illuminate\Pagination\Paginator::resolveCurrentPage() ?: 1);
		$users = $users instanceof \Illuminate\Support\Collection ? $users : \Illuminate\Support\Collection::make($users);
		$users = new LengthAwarePaginator($users->forPage($page, 50), $users->count(), 50, $page, ['path' => $request->url(), 'query' => $request->query()]);

		return view('users.user', compact('user', 'users', 'rawusers'));
	}

	public function report() {
		return view('users.report');
	}

	public function exportReport(Request $request)
	{
		$users = User::where('role', '<>', 'customer')->get();
		$month = $request->month;
		$data = [];
		foreach($users as $user) {
			$name = $user->username;
			$active_customers = User::where('active', 1)->where('created_by', $user->id)->where('role', 'customer')->where('trial_ends_at', '>=', date('Y-m-d H:i:s'))->count();
			$total_customers = User::where('active', 1)->where('created_by', $user->id)->where('role', 'customer')->count();
			$loyalty = User::where('active', 1)->where('created_by', $user->id)->where('role', 'customer')->where('trial_ends_at', '>=', date('Y-m-d H:i:s'))->where('created_at', '>=', date('Y-m-d H:i:s', time() - 60*60*24*30*$month))->count();
			$loyalty1 = User::where('active', 1)->where('created_by', $user->id)->where('role', 'customer')->where('trial_ends_at', '>=', date('Y-m-d H:i:s'))->where('created_at', '>=', date('Y-m-d H:i:s', time() - 60*60*24*30))->count();
			$loyalty2_3 = User::where('active', 1)->where('created_by', $user->id)->where('role', 'customer')->where('trial_ends_at', '>=', date('Y-m-d H:i:s'))->where('created_at', '>=', date('Y-m-d H:i:s', time() - 60*60*24*30*3))->count();
			$loyalty4 = User::where('active', 1)->where('created_by', $user->id)->where('role', 'customer')->where('trial_ends_at', '>=', date('Y-m-d H:i:s'))->where('created_at', '<', date('Y-m-d H:i:s', time() - 60*60*24*30*4))->count();
			array_push(
				$data, 
				[
					'distributor' => $name, 
					'active_customers' => $active_customers, 
					'total_customers' => $total_customers, 
					'loyalty' => $active_customers == 0 ? 0 : number_format(($loyalty*100)/$active_customers, 2).'%', 
					'loyalty1' => $active_customers == 0 ? 0 : number_format(($loyalty1*100)/$active_customers, 2).'%',
					'loyalty2_3' => $active_customers == 0 ? 0 : number_format(($loyalty2_3*100)/$active_customers, 2).'%',
					'loyalty4' => $active_customers == 0 ? 0 : number_format(($loyalty4*100)/$active_customers, 2).'%'
				]
			);
		}
		Excel::create('report', function($excel) use($data) {

			$excel->sheet('Report', function($sheet) use($data) {
		
				$sheet->fromArray($data);
		
			});
		
		})->download('xlsx');

		return back();
	}
}