<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Error extends Model {

    protected $table = 'errors';

	protected $fillable = [
        'type',
        'video_id',
        'episode_id',
        'status',
    ];

    public function users(){
        return $this->belongsToMany(User::class);
    }

    public function video(){
        return $this->belongsTo(Video::class);
    }

    public function episode(){
        return $this->belongsTo(Episode::class);
    }

    public function error_user(){
        return $this->hasMany(ErrorUser::class);
    }
}