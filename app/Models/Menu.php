<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Menu extends Model {

	protected $table = 'menu';

    protected $fillable = [
        'parent_id',
        'order',
        'name',
        'url',
        'type'
    ];

	public function menu(){
        return $this->belongsTo(Menu::class, 'parent_id');
	}

    public function hasChildren(){
        if(\DB::table('menu')->where('parent_id', $this->id)->count() >= 1){
            return true;
        } else {
            return false;
        }
    }

}