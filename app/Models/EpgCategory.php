<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
class EpgCategory extends Model {
	protected $table = 'epg_categories';

	public $fillable = ['name', 'parent_id', 'order'];

	public function channels(){
		return $this->hasMany(Newchannel::class, 'category_id');
	}

	public function category(){
	    return $this->belongsTo(EpgCategory::class);
	}

    public function hasChildren(){
        if(\DB::table('epg_categories')->where('parent_id', $this->id)->count() >= 1){
            return true;
        } else {
            return false;
        }
    }
}