<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    protected $table = 'subscriptions';

	protected $fillable = [
	    'user_id',
        'name',
        'stripe_id',
        'stripe_plan',
        'quantity',
        'trial_ends_at',
        'ends_at',
        'created_at'
        ];

	public function user(){
	    return $this->belongsTo(User::class);
    }
}
