<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Setting;
use App\Models\Favorite;
use App\Models\VideoView;

class Video extends Model {
    
    protected $table = 'videos';

    public static $rules = array();

	protected $fillable = [
	    'user_id',
        'edited_by',
        'video_category_id',
        'title',
        'title_es',
        'type',
        'access',
        'plot',
        'released_at',
        'active',
        'featured',
        'runtime',
        'actors',
        'director',
        'imdb_rating',
        'rating',
        'image',
        'backdrop',
        'logo',
        'trailer',
        'video_file_name',
        'subtitle_file_name',
        'subtitle_file_name_es',
        'hls_link',
        'hd',
        'en',
        'es',
        'created_at',
        'link_works',
        'link_subtitle_works',
        'link_subtitle_es_works',
        'kids_zone'
    ];

    protected $appends = ['full_image', 'full_backdrop', 'full_logo', 'stream', 'stream_format', 'full_subtitle_file_name', 'full_subtitle_file_name_es'];

    public function assigned_user(){
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function category(){
        return $this->belongsTo(VideoCategory::class, 'video_category_id');
    }

    public function editor(){
        return $this->belongsTo(User::class, 'edited_by');
    }

    public function favorites(){
        return $this->hasMany(Favorite::class);
    }

    public function video_views(){
        return $this->hasMany(VideoView::class);   
    }

    public function errors(){
        return $this->hasMany(Error::class);
    }

	public function user(){
	    return $this->belongsTo(User::class);
    }

    public function getFullBackdropAttribute(){
        if(!empty($this->backdrop)){
            return 'http://d3ilto9zkrmwmz.cloudfront.net/'.$this->backdrop;
        }
        return '';        
    }

    public function getFullLogoAttribute(){
        if(!empty($this->logo)){
            return 'http://d3ilto9zkrmwmz.cloudfront.net/'.$this->logo;
        }
        return '';        
    }

    public function getFullImageAttribute(){
        if(!empty($this->image)){
            return 'http://d3ilto9zkrmwmz.cloudfront.net/'.pathinfo($this->image, PATHINFO_DIRNAME).'/'.pathinfo($this->image, PATHINFO_FILENAME) . '-small.' . pathinfo($this->image, PATHINFO_EXTENSION);
        }
        return '';        
    }

    public function getStreamAttribute(){
        $settings = Setting::first();
        if(!empty($this->video_file_name)){
            return $settings->server_link.$this->video_file_name;
        }
        return '';
    }

    public function getStreamFormatAttribute(){
        if(!empty($this->video_file_name)){
            return substr($this->video_file_name, strrpos($this->video_file_name, '.') + 1);
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

    public function getRuntimeAttribute($value){
        return intval($value) * 60;
    }

    public function scopePosition1($query, $user){
        return $query->addSelect(['position' => VideoView::select(\DB::raw('IFNULL(position, 0) as position'))
            ->whereColumn('video_id', 'videos.id')
            ->where('user_id', $user->id)
            ->limit(1)
        ]);
    }

    public function scopeIsFavorite1($query, $user){
        return $query->addSelect(['is_favorite' => Favorite::select(\DB::raw('count(id) as is_favorite'))
            ->whereColumn('video_id', 'videos.id')
            ->where('user_id', $user->id)
            ->limit(1)
        ]);
    }

    public function scopeIsSeen($query, $user){
        return $query->addSelect(['is_seen' => VideoView::select(\DB::raw('count(id) as is_seen'))
            ->whereColumn('video_id', 'videos.id')
            ->where('user_id', $user->id)
            ->limit(1)
        ]);
    }

    public function position($user) {
        $position = $this->video_views()->where('user_id', $user->id)->first();
        return ($position) ? $position->position : 0;
    }

    public function isFavorite($user){
        return Favorite::where('user_id', $user->id)->where('video_id', $this->id)->count();
    }

    public function is_seen($user) {
        $viewed = VideoView::where('video_id', $this->id)->where('user_id', $user->id)->count();
        if($viewed){
            return true;
        }
        return false;
    }

    public function status() {
        return Video::where('id', $this->id)
                ->where('link_works', 1)
                ->where('link_subtitle_works', 1)
                ->where('link_subtitle_es_works', 1)
                ->first();
    }
}