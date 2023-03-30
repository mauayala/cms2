<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Team extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'logo'];

    public function options()
    {
        return $this->belongsToMany(Option::class);
    }

    public function getLogoAttribute($value){
        return 'http://d3ilto9zkrmwmz.cloudfront.net/'.pathinfo($value, PATHINFO_DIRNAME).'/'.pathinfo($value, PATHINFO_FILENAME) . '.' . pathinfo($value, PATHINFO_EXTENSION);
    }
}
