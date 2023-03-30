<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Favorite extends Model {

	protected $table = 'favorites';

	protected $fillable = ['user_id', 'video_id', 'serie_id'];

	public function user(){
		return $this->belongsTo(User::class);
	}

	public function video(){
		return $this->belongsTo(Video::class);
	}

	public function serie(){
		return $this->belongsTo(Serie::class);
	}
}