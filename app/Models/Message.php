<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Message extends Model {

    protected $table = 'messages';

	protected $fillable = [
        'content',
        'user_id',
        'status'
        ];

    public function users(){
        return $this->belongsTo(User::class);
    }
}