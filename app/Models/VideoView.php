<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VideoView extends Model {
	protected $table = 'video_view';

	public $fillable = ['position', 'video_id', 'episode_id', 'user_id'];

	public function video(){
		return $this->belongsTo(Video::class);
	}

	public function episode(){
		return $this->belongsTo(Episode::class);
	}

	public function user(){
	    return $this->hasOne(User::class);
	}
}