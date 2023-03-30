<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Newchannel extends Model {
    
    public function category()
    {
        return $this->hasOne(EpgCategory::class, 'id', 'category_id');
    }

    public function programs()
    {
        return $this->hasMany(Program::class, 'channel_id');
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