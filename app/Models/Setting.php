<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model {

    protected $table = 'settings';

	protected $fillable = [
		'website_name', 'website_description', 'logo', 'favicon', 'system_email', 'facebook_page_id', 'google_page_id', 
		'twitter_page_id', 'youtube_page_id', 'google_tracking_id', 'google_oauth_key', 'server_link', 'subtitle_link', 
		'ip_domain', 'live_username', 'live_password', 'login_background'
	];

	public $timestamps = false;

	public function getLoginBackgroundAttribute($value)
	{
		return env('APP_URL').'/settings/'.$value;
	}
}