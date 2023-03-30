<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Credit extends Model {

    protected $table = 'credits';

	protected $fillable = [
        'from_user_id',
        'to_user_id',
        'amount',
        'from_credit_amount',
        'to_credit_amount',
        ];

    public function fromuser(){
        return $this->belongsTo(User::class, 'from_user_id');
    }

    public function touser(){
        return $this->belongsTo(User::class, 'to_user_id');
    }
}