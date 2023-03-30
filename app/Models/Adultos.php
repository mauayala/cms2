<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Adultos extends Model {

    protected $table = 'adultos';

	protected $fillable = [
	    'user_id',
        'edited_by',
        'adultos_category_id',
        'title',
        'type',
        'access',
        'plot',
        'released_at',
        'active',
        'featured',
        'runtime',
        'actors',
        'rating',
        'image',
        'backdrop',
        'video_file_name',
        'hls_link',
        'hd',
        'created_at'
        ];


	public function user(){
	    return $this->belongsTo(User::class);
    }

    public function category(){
        return $this->belongsTo(AdultosCategory::class, 'adultos_category_id');
    }

    public function editor(){
        return $this->belongsTo(User::class, 'edited_by');
    }

    public function getFullBackdropAttribute(){
        if(!empty($this->backdrop)){
            return 'http://d3ilto9zkrmwmz.cloudfront.net/'.$this->backdrop;
        }
        return '';        
    }

    public function getFullImageAttribute(){
        if(!empty($this->image)){
            return 'http://d3ilto9zkrmwmz.cloudfront.net/'.pathinfo($this->image, PATHINFO_DIRNAME).'/'.pathinfo($this->image, PATHINFO_FILENAME) . '-small.' . pathinfo($this->image, PATHINFO_EXTENSION);
        }
        return '';        
    }
}