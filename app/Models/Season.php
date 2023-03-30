<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Season extends Model {

    protected $table = 'seasons';

    protected $touches = ['serie'];

	protected $fillable = [
        'user_id',
        'edited_by',
        'actors',
        'released_at',
        'featured',
        'runtime',
        'poster',
        'imdb_rating',
        'rating',
        'hd',
        'serie_id',
        'season_number'
        ];

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function editor(){
        return $this->belongsTo(User::class, 'edited_by');
    }

    public function episodes(){
        return $this->hasMany(Episode::class);
    }

    public function serie(){
        return $this->belongsTo(Serie::class);
    }

    public function hasErrors() {
        foreach($this->episodes as $e){
            if($e->link_works == 0){
                return true;
            }
        }
        return false;
    }

    public function season_views()
    {
        $season_id = $this->id;
        return \App\Models\VideoView::whereHas('episode', function($q) use($season_id){
            $q->where('season_id', $season_id);
        })->get();
    }

    public function getFullPosterAttribute(){
        if(!empty($this->poster))
            return 'http://d3ilto9zkrmwmz.cloudfront.net/'.pathinfo($this->poster, PATHINFO_DIRNAME).'/'.pathinfo($this->poster, PATHINFO_FILENAME) . '-small.' . pathinfo($this->poster, PATHINFO_EXTENSION);
        return '';
    }
}