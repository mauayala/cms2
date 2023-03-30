<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
class Tag extends Model {
    protected $table = 'tags';
    protected $fillable = ['name'];

	public function videos(){
		return $this->belongsToMany(Video::class);
	}

	public function series(){
		return $this->belongsToMany(Serie::class);
	}
}