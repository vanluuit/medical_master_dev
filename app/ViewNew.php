<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ViewNew extends Model
{
    //
    protected $table = 'view_news';
    const UPDATED_AT = null;
    protected $fillable = [
        'user_id','new_id'
    ];
    public function user()
    {
        return $this->beLongsTo('App\User', 'user_id');
    }
}
