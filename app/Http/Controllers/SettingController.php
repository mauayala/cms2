<?php namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Setting;
use App\Models\User;
use App\Models\Video;
use App\Models\Episode;
use App\Libraries\ImageHandler;
use Hash;
use App\Exports\MovieAndEpisodeList;

class SettingController extends Controller {

	public function index()
	{
		$settings = Setting::first();
		return view('settings.index', compact('settings'));
	}

	public function update(Request $request){
		$settings = Setting::first();
		$settings->website_name = $request->website_name;
		$settings->website_description = $request->website_description;
		
		if($request->hasFile('logo')){
        	$settings->logo = ImageHandler::uploadImage($request->file('logo'), 'settings', 'logo.png');
        }

        if($request->hasFile('favicon')){
        	$settings->favicon = ImageHandler::uploadImage($request->file('favicon'), 'settings', 'favicon.png');
        }

		if($request->hasFile('login_background')){
        	$settings->login_background = ImageHandler::uploadImage($request->file('login_background'), 'settings', 'login_background.png');
        }

        $settings->xml_link = $request->xml_link;
        $settings->system_email = $request->system_email;
        $settings->facebook_page_id = $request->facebook_page_id;
        $settings->google_page_id = $request->google_page_id;
        $settings->twitter_page_id = $request->twitter_page_id;
        $settings->youtube_page_id = $request->youtube_page_id;
		$settings->google_tracking_id = $request->google_tracking_id;
		$settings->google_oauth_key = $request->google_oauth_key;
		$settings->server_link = $request->server_link;
		$settings->subtitle_link = $request->subtitle_link;
		$settings->trailer_link = $request->trailer_link;
		$settings->ip_domain = $request->ip_domain;
		$settings->live_username = $request->live_username;
		$settings->live_password = $request->live_password;
		$settings->save();

        return redirect('dashboard/settings');
	}

	public function movie_episode_list()
	{
		return view('settings.movie-episode-list');
	}

	public function export(Request $request)
	{
		$export = new MovieAndEpisodeList($request->month, $request->year);
		return \Excel::download($export, 'movie_and_episode_list.xlsx');
	}

	public function change_password()
	{
		return view('settings.change_password');
	}

	public function update_user(Request $request){
		$admin = User::where('username', $request->admin)->first();
		$user = User::find(\Auth::user()->id);
		if($request->password != ''){
            $user->password = Hash::make($request->password);
        }
        if($request->admin != ''){
        	if($admin){
				if(
					($user->role == 'seller' && $admin->role == 'distributor') || 
					($user->role == 'distributor' && $admin->role == 'admin') ||
					($user->role == 'seller' && $admin->role == 'staff')	
				){
					$user->created_by = $admin->id;
				} else {
					return back()->with(['note' => 'Enter valid username', 'note_type' => 'error']);
				}
			} else {
				return back()->with(['note' => 'Enter valid username', 'note_type' => 'error']);
			}
        }	

		$user->save();			
		return back()->with(['note' => 'Successfully saved', 'note_type' => 'success']);
	}

	public function gitToMaster()
	{
		$old_path = getcwd();
        $output = shell_exec('/opt/scripts/MergeDevToMaster.sh');
		chdir($old_path);

		echo $output;
	}
}