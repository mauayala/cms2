<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
class SerieCategory extends Model {
	protected $table = 'serie_categories';

	public $fillable = ['name', 'slug', 'parent_id', 'order'];
	
	public function series(){
		return $this->hasMany(Serie::class);
	}

	public function category(){
	    return $this->belongsTo(SerieCategory::class);
	}

    public function hasChildren(){
        if(\DB::table('serie_categories')->where('parent_id', $this->id)->count() >= 1){
            return true;
        } else {
            return false;
        }
    }
}