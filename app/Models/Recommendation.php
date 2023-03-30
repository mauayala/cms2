<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Recommendation extends Model
{
    use HasFactory;

    protected $fillable = ['video_id', 'serie_id', 'order'];

    public function serie()
    {
        return $this->belongsTo(Serie::class);
    }

    public function video()
    {
        return $this->belongsTo(Video::class);
    }
}
