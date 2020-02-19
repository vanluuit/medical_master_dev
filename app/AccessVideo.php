<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AccessVideo extends Model
{
    //
    protected $table = 'access_videos';
    const UPDATED_AT = null;
    protected $fillable = [
        'id','video_id','user_id'
    ];
}
