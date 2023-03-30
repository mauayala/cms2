<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Favorite;

class Serie extends Model {

    protected $table = 'series';

    public static $rules = array();

	protected $fillable = [
        'user_id',
        'assigned_to',
        'edited_by',
        'serie_category_id',
        'title',
        'title_es',
        'access',
        'plot',
        'released_at',
        'active',
        'runtime',
        'actors',
        'director',
        'imdb_rating',
        'rating',
        'image',
        'backdrop',
        'featured_backdrop',
        'hd',
        'multiseasoned',
        'trailer',
        'season_number',
        'episode_number',
        'monday',
        'tuesday',
        'wednesday',
        'thursday',
        'friday',
        'saturday',
        'sunday',
        'status',
        'link_subtitle_works',
        'link_subtitle_es_works',
        'kids_zone'
        ];

    protected $appends = ['full_image', 'full_backdrop', 'full_logo'];

    public function favorites(){
        return $this->hasMany(Favorite::class);
    }

    public function tags(){
        return $this->belongsToMany(Tag::class);
    }

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function category(){
        return $this->belongsTo(SerieCategory::class, 'serie_category_id');
    }

    public function editor(){
        return $this->belongsTo(User::class, 'edited_by');
    }

    public function seasons(){
        return $this->hasMany(Season::class);
    }

    public function episodes(){
        return $this->hasMany(Episode::class);
    }

    public function hasErrors() {
        if($this->seasons()->whereHas('episodes', function($q){
            $q->where('link_works', 0);
        })->count() == 0) {
            return true;
        }
        return false;
    }

    public function video_views()
    {
        return $this->hasManyThrough(VideoView::class, Episode::class);
    }

    public function serie_views()
    {
        $serie_id = $this->id;
        return \App\Models\VideoView::whereHas('episode', function($q) use($serie_id){
            $q->where('serie_id', $serie_id);
        })->get();
    }

    public function scopeIsFavorite1($query, $user){
        return $query->addSelect(['is_favorite' => Favorite::select(\DB::raw('count(id) as is_favorite'))
            ->whereColumn('serie_id', 'series.id')
            ->where('user_id', $user->id)
            ->limit(1)
        ]);
    }

    public function getFullImageAttribute(){
        if(!empty($this->image))
            return 'http://d3ilto9zkrmwmz.cloudfront.net/'.pathinfo($this->image, PATHINFO_DIRNAME).'/'.pathinfo($this->image, PATHINFO_FILENAME) . '-small.' . pathinfo($this->image, PATHINFO_EXTENSION);
        return '';
    }

    public function getFullBackdropAttribute(){
        if(!empty($this->backdrop))
            return 'http://d3ilto9zkrmwmz.cloudfront.net/'.$this->backdrop;
        return '';
    }

    public function getFullLogoAttribute(){
        if(!empty($this->featured_backdrop))
            return 'http://d3ilto9zkrmwmz.cloudfront.net/'.$this->featured_backdrop;
        return '';
    }

    public function getRuntimeAttribute($value){
        return intval($value) * 60;
    }

    public function isFavorite($user){
        return Favorite::where('user_id', $user->id)->where('serie_id', $this->id)->count();
    }    

    public function season_episode() {
        $season_number = $episode_number = '';
        if($this->season_number < 10) {
            $season_number = '0'.$this->season_number;
        } else {
            $season_number = $this->season_number;
        }
        if(isset($this->episode_number) && $this->episode_number < 10) {
            $episode_number = '0'.$this->episode_number;
        } elseif(isset($this->episode_number)) {
            $episode_number = $this->episode_number;
        }
        return 'S'.$season_number.'E'.$episode_number;
    }

    public function status() {
        return Episode::where('serie_id', $this->serie_id)
                ->where('season_episode', $this->season_episode())
                ->where('link_works', 1)
                ->where('link_subtitle_works', 1)
                ->where('link_subtitle_es_works', 1)
                ->first();
    }

    public function link_works() {
        if(Episode::where('serie_id', $this->serie_id)
                ->where('season_episode', $this->season_episode())
                ->where('link_works', 1)
                ->first()
        ) {
            return 1;
        } else {
            return 0;
        }
    }

    public function link_subtitle_works() {
        if($this->link_subtitle_works == 1 &&
            Episode::where('serie_id', $this->serie_id)
                ->where('season_episode', $this->season_episode())
                ->where('link_subtitle_works', 1)
                ->first()
        ) {
            return 1;
        } elseif($this->link_subtitle_works == 1) {
            return 0;
        }
        return 2;
    }

    public function link_subtitle_es_works() {
        if($this->link_subtitle_es_works == 1 &&
            Episode::where('serie_id', $this->serie_id)
                ->where('season_episode', $this->season_episode())
                ->where('link_subtitle_es_works', 1)
                ->first()
        ) {
            return 1;
        } elseif($this->link_subtitle_es_works == 1) {
            return 0;
        }
        return 2;
    }
}