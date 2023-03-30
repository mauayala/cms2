<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Models\VideoView;
use App\Models\Credit;
use App\Models\Episode;

class User extends Authenticatable {
    use Notifiable;

    protected $fillable = [
        'username',
        //'active',
        'email',
        'password',
        'role',
        'status',
        'trial_ends_at',
        'ip',
        'created_by',
        'pin',
        'firstname',
        'lastname',
        'phone',
        'devicenumber',
        'devicenumber2',
        'access_token',
        'access_token_lifetime',
        'credit_amount',
        'parent_control',
        'model',
        'model2',
        'app_version_id'
    ];

    protected $hidden = [
        'remember_token', 'password'
    ];

    public function getFullNameAttribute()
    {
        return $this->firstname.' '.$this->lastname;
    }

    public function assigned_movies()
    {
        return $this->hasMany(Video::class, 'assigned_to');
    }

    public function creator(){
        return $this->belongsTo(User::class, 'created_by');
    }

    public function isOnline()
    {
        $watching = VideoView::where('user_id', $this->id)->where('updated_at', '>=', date('Y-m-d H:i:s', time()-60))->first();
        if(isset($watching->id)){
            return (is_null($watching->video_id)) ? $watching->episode->title : $watching->video->title;
        } else {
            return 'Offline';
        }
    }
    
    public function childrenCount(){
        return User::where('created_by', $this->id)->whereDate('trial_ends_at', '>', date('Y-m-d H:i:s'))->count();
    }

    // amount of credits bought in last 30 days
    public function boughtLastMonth(){
        return Credit::where('to_user_id', $this->id)->whereDate('created_at', '>', date('Y-m-d H:i:S', time()-86400*30))->sum('amount');
    }

    public function checked_episodes() {
        return Episode::where('checked_by', $this->id)->count();
    }

    public function assigned_series() {
        return $this->hasMany(Serie::class, 'assigned_to');
    }

    public function role()
    {
        return $this->belongsTo(Role::class);
    }
}
