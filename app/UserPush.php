<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserPush extends Model
{
    //
    protected $table = 'user_pushs';
    const UPDATED_AT = null;
    protected $fillable = [
        'user_id','rss','channel','content','discussion'
    ];
}
