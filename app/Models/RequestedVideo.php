<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RequestedVideo extends Model {

    protected $table = 'requested_videos';

	protected $fillable = [
        'title',
        'status',
        ];
}