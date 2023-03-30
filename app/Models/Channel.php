<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Channel extends Model {

    protected $table = 'channels';

	protected $fillable = [
        'channel_id',
        'name',
        'logo',
        'category_id',
        'link',
        'active',
        'link_live',
        'visible',
        'is_premiered'
        ];

    public function category()
    {
        return $this->hasOne(EpgCategory::class, 'id', 'category_id');
    }
}