<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
class VideoCategory extends Model {
	protected $table = 'video_categories';

	public $fillable = ['name', 'slug', 'parent_id', 'order'];

	public function videos(){
		return $this->hasMany(Video::class);
	}

	public function category(){
	    return $this->belongsTo(VideoCategory::class);
	}

    public function hasChildren(){
        if(\DB::table('video_categories')->where('parent_id', $this->id)->count() >= 1){
            return true;
        } else {
            return false;
        }
    }
}