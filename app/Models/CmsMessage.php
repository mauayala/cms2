<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
class CmsMessage extends Model {
	protected $table = 'cms_messages';

	public function user(){
	    return $this->belongsTo(User::class);
    }
    
    public function from_user(){
	    return $this->belongsTo(User::class, 'from_user_id');
	}
}