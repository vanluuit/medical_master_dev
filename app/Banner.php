<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Banner extends Model
{
    //
     protected $table = 'banners';
     protected $fillable = [
        'category_id', 'image', 'video_id', 'type', 'url', 'start_date','end_date', 'location'
    ];
    public function video()
    {
        return $this->beLongsTo('App\Video', 'video_id', 'id');
    }
    public function category()
    {
        return $this->beLongsTo('App\Category', 'category_id', 'id');
    }
    public function banner_views()
    {
        return $this->hasMany('App\ViewBanner', 'banner_id', 'id');
    }
}
