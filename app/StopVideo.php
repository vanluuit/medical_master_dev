<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StopVideo extends Model
{
    //
    protected $table = 'stop_videos';
    const UPDATED_AT = null;
    protected $fillable = [
        'user_id','video_id','time_stop','time_total'
    ];
}
