<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Event extends Model {

	public function options(){
		return $this->hasMany(Option::class);
	}
}