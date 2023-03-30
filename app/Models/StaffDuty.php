<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StaffDuty extends Model 
{
	protected $fillable = ['name', 'day_number', 'user_id', 'max_broken_link'];

	public function user(){
		return $this->belongsTo(User::class);
	}
}