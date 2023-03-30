<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Option extends Model {

	public function event(){
		return $this->belongsTo(Event::class);
	}

    public function teams()
    {
        return $this->belongsToMany(Team::class);
    }

	public function getLinkAttribute($value)
    {
        if(substr($value, 0, 4) == 'http') {
            return $value;
        } else {
            $setting = \App\Models\Setting::first();
            return 'http://'.$setting->ip_domain.'/live/'.$setting->live_username.'/'.$setting->live_password.'/'.$value;
        }
    }
}