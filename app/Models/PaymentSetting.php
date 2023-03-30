<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentSetting extends Model {
	public $timestamps = false;

	protected $fillable = [
	    'live_mode',
        'test_secret_key',
        'test_publishable_key',
        'live_secret_key',
        'live_publishable_key'
    ];
}