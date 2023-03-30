<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Program extends Model {

    protected $table = 'programs';

	protected $fillable = [
        'start',
        'stop',
        'channel',
        'title',
        'subtitle'
        ];

    public function channel()
    {
        return $this->belongsTo(Newchannel::class, 'channel_id', 'channel');
    }
}