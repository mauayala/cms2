<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ErrorUser extends Model {

    protected $table = 'error_user';

	protected $fillable = [
        'user_id',
        'error_id'
    ];
}