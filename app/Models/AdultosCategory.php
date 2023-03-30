<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
class AdultosCategory extends Model {
	protected $table = 'adultos_categories';

	public $fillable = ['name', 'slug', 'parent_id', 'order'];

	public function adultos(){
		return $this->hasMany(Adultos::class);
	}

	public function category(){
	    return $this->belongsTo(AdultosCategory::class);
	}

    public function hasChildren(){
        if(\DB::table('adultos_categories')->where('parent_id', $this->id)->count() >= 1){
            return true;
        } else {
            return false;
        }
    }
}