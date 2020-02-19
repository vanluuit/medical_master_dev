<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AccessNew extends Model
{
    //
    protected $table = 'access_news';
    const UPDATED_AT = null;
    protected $fillable = [
        'id','new_id','user_id'
    ];
}
