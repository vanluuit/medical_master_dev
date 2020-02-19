<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PushUser extends Model
{
    //
    protected $table = 'push_users';
    const UPDATED_AT = null;
    protected $fillable = [
        'username','password','category_id'
    ];
    public function category()
    {
        return $this->beLongsTo('App\Category', 'category_id');
    }
}
