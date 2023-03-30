<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
class VideoTag extends Model {
	protected $table = 'tag_video';

    public function tags(){
        return $this->hasMany(Tag::class);
    }

    public function videos(){
        return $this->hasMany(Video::class);
    }
}