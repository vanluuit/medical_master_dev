<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ViewVideo extends Model
{
    //
    protected $table = 'view_videos';
    const UPDATED_AT = null;
    protected $fillable = [
        'user_id','video_id','time_view','time_total'
    ];
    public function user()
    {
        return $this->beLongsTo('App\User', 'user_id');
    }
    public function content()
    {
        return $this->beLongsTo('App\Video', 'video_id');
    }
}
