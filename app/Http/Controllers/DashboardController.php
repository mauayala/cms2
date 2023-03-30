<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Models\CmsMessage;
use App\Models\Episode;
use App\Models\Serie;
use App\Models\StaffDuty;
use App\Models\User;
use App\Models\Video;

class DashboardController extends Controller
{
	public function index()
	{
        if(\Auth::user()->role == 'staff') {
            $totalSubscribers = User::where('active', 1)
                ->where('role', 'customer')
                ->where('trial_ends_at', '>=', date('Y-m-d H:i:s'))
                ->where('created_by', \Auth::user()->id)
                ->count();

            $newSubscribers = User::where('active', 1)
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

            $totalVideos = 0;
            $totalSeries = 0;
        } else {
            $totalSubscribers = User::where('active', 1)
                ->where('role', 'customer')
                ->where('trial_ends_at', '>=', date('Y-m-d H:i:s'))
                ->count();

            $newSubscribers = User::where('active', 1)
                ->where('role', 'customer')
                ->where('trial_ends_at', '>=', date('Y-m-d H:i:s'))
                ->whereMonth('created_at', date('m'))
                ->whereYear('created_at', date('Y'))
                ->count();

            $totalVideos = Video::where('active', 1)->count();

            $totalSeries = Serie::where('active', 1)->count();

            $online_customers = 0;
        }

        $action = 'dashboard';
        return view('dashboard.index', compact('action','totalSubscribers', 'newSubscribers', 'totalVideos', 'totalSeries', 'online_customers'));
    }
    
    public function search(Request $request)
    {
        $title_es1 = str_replace('s', 'z', $request->s);
        $title_es2 = str_replace('z', 's', $request->s);
        
        $videos = Video::where('title', 'like', '%'.$request->s.'%')
                            ->orWhere('title_es', 'like', '%'.$title_es1.'%')
                            ->orWhere('title_es', 'like', '%'.$title_es2.'%')
                            ->orWhereRaw('REPLACE(title, "-", " ") LIKE ?', ['%'.$request->s.'%'])
                            ->orWhereRaw('REPLACE(title_es, "-", " ") LIKE ?', ['%'.$title_es1.'%'])
                            ->orWhereRaw('REPLACE(title_es, "-", " ") LIKE ?', ['%'.$title_es2.'%'])
                            ->orderBy(\DB::raw('CASE
                                WHEN title LIKE "'.$request->s.'%" THEN 1
                                WHEN title LIKE "%'.$request->s.'" THEN 3
                                ELSE 2
                            END'))
                            ->take(15)
                            ->get();
        $series = Serie::where('title', 'like', '%'.$request->s.'%')
                            ->orWhere('title_es', 'like', '%'.$title_es1.'%')
                            ->orWhere('title_es', 'like', '%'.$title_es2.'%')
                            ->orWhereRaw('REPLACE(title, "-", " ") LIKE ?', ['%'.$request->s.'%'])
                            ->orWhereRaw('REPLACE(title_es, "-", " ") LIKE ?', ['%'.$title_es1.'%'])
                            ->orWhereRaw('REPLACE(title_es, "-", " ") LIKE ?', ['%'.$title_es2.'%'])
                            ->orderBy(\DB::raw('CASE
                                WHEN title LIKE "'.$request->s.'%" THEN 1
                                WHEN title LIKE "%'.$request->s.'" THEN 3
                                ELSE 2
                            END'))
                            ->take(15)
                            ->get();

        if(\Auth::user()->role == 'owner') {
            $users = User::where('username', 'like', '%'.$request->s.'%')->get();
        } else {
            $users = User::where('username', 'like', '%'.$request->s.'%')->where('created_by', \Auth::user()->id)->get();
        }
    
        return view('dashboard.search', compact('videos', 'series', 'users'));
    }

    public function update_stats(){
        $totalSubscribers = User::where('active', 1)
                                ->where('role', 'customer')
                                ->where('trial_ends_at', '>', date('Y-m-d H:i:s'))
                                ->count();

        $newSubscribers = User::where('active', 1)
                        ->where('role', 'customer')
                        ->where('trial_ends_at', '>', date('Y-m-d H:i:s'))
                        ->whereMonth('created_at', date('m'))
                        ->whereYear('created_at', date('Y'))
                        ->count();

        $totalVideos = count(Video::where('active', 1)->get());

        $totalSeries = count(Serie::where('active', 1)->get());
        return [$totalSubscribers, $newSubscribers, $totalVideos, $totalSeries];
    }
}
