<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserNew extends Model
{
    //
    protected $table = 'user_news';
    protected $fillable = [
        'user_id','new_id'
    ];
}
