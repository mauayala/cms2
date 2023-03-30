<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
class SerieTag extends Model {
	protected $table = 'tag_serie';

    public function tags(){
        return $this->hasMany(Tag::class);
    }

    public function series(){
        return $this->hasMany(Serie::class);
    }
}