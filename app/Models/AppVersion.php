<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AppVersion extends Model
{
    use HasFactory;

    public $fillable = ['link', 'version'];

    public function getLinkAttribute($value){
        return 'http://d3ilto9zkrmwmz.cloudfront.net/'.$value;
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }
}
