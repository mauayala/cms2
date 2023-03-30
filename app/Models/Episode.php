<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Setting;
use App\Models\VideoView;

class Episode extends Model {

    protected $table = 'episodes';

    protected $touches = ['serie'];
    
	protected $fillable = [
        'user_id',
        'edited_by',
        'title',
        'plot',
        'released_at',
        'featured',
        'runtime',
        'actors',
        'director',
        'imdb_rating',
        'rating',
        'poster',
        'serie_file_name',
        'subtitle_file_name',
        'subtitle_file_name_es',
        'hls_link',
        'hd',
        'season_id',
        'serie_id',
        'episode_number',
        'season_episode',
        'air_date',
        'en',
        'es'
    ];

    protected $appends = ['full_poster', 'stream', 'stream_format', 'full_subtitle_file_name', 'full_subtitle_file_name_es'];

    public function serie(){
        return $this->belongsTo(Serie::class);
    }

    public function season(){
        return $this->belongsTo(Season::class);
    }

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function editor(){
        return $this->belongsTo(User::class, 'edited_by');
    }

    public function episode_views(){
        return $this->hasMany(VideoView::class);   
    }

    public function errors(){
        return $this->hasMany(Error::class);
    }

    public function getFullPosterAttribute(){
        if(!empty($this->poster))
            return 'http://d3ilto9zkrmwmz.cloudfront.net/'.pathinfo($this->poster, PATHINFO_DIRNAME).'/'.pathinfo($this->poster, PATHINFO_FILENAME) . '-small.' . pathinfo($this->poster, PATHINFO_EXTENSION);
        return '';
    }

    public function getStreamAttribute(){
        $settings = Setting::first();
        if(!empty($this->serie_file_name)){
            return $settings->server_link.$this->serie_file_name;
        }
        return '';
    }

    public function getStreamFormatAttribute(){
        if(!empty($this->serie_file_name)){
            return substr($this->serie_file_name, strrpos($this->serie_file_name, '.') + 1);
        }
        return '';
    }

    public function getStreamhlsAttribute(){
        return $this->hls_link;
    }

    public function getStreamFormathlsAttribute(){
        if(!empty($this->hls_link)){
            return substr($this->hls_link, strrpos($this->hls_link, '.') + 1);
        }
        return '';
    }

    public function getFullSubtitleFileNameAttribute(){
        $settings = Setting::first();
        if(!empty($this->subtitle_file_name)){
            return $settings->subtitle_link.$this->subtitle_file_name;
        }
        return '';
    }

    public function getFullSubtitleFileNameEsAttribute(){
        $settings = Setting::first();
        if(!empty($this->subtitle_file_name_es)){
            return $settings->subtitle_link.$this->subtitle_file_name_es;
        }
        return '';
    }

    public function scopeIsSeen($query, $user){
        return $query->addSelect(['is_seen' => VideoView::select(\DB::raw('count(id) as is_seen'))
            ->whereColumn('episode_id', 'episodes.id')
            ->where('user_id', $user->id)
            ->limit(1)
        ]);
    }

    public function position($user){
        $position = $this->episode_views()->where('user_id', $user->id)->first();
        return ($position) ? $position->position : 0;
    }

    public function is_seen($user) {
        $viewed = VideoView::where('episode_id', $this->id)->where('user_id', $user->id)->count();
        if($viewed){
            return true;
        }
        return false;
    }

    public function link_works() {
        if(Episode::where('serie_id', $this->serie_id)
                ->where('season_episode', $this->season_episode)
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
                ->where('season_episode', $this->season_episode)
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
                ->where('season_episode', $this->season_episode)
                ->where('link_subtitle_es_works', 1)
                ->first()
        ) {
            return 1;
        } elseif($this->link_subtitle_es_works == 1) {
            return 0;
        }
        return 2;
    }

    public function status() {
        return Episode::where('serie_id', $this->serie_id)
                ->where('season_episode', $this->season_episode)
                ->where('link_works', 1)
                ->where('link_subtitle_works', 1)
                ->where('link_subtitle_es_works', 1)
                ->first();
    }
}