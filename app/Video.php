<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Video extends Model
{
    //
    protected $table = 'videos';
    protected $fillable = [
         'channel_id', 'title', 'discription', 'thumbnail','thumbnail_detail', 'video', 'pdf', 'sponser', 'start_date', 'end_date', 'ads_text', 'ads_url', 'ads_text_2', 'ads_url_2', 'nabi', 'related_videos_1', 'related_videos_2', 'related_videos_3', 'type', 'view', 'publish', 'Nonrepresentation', 'banner', 'url'
    ];
    public function count_like()
    {
        return $this->hasMany('App\LikeVideo', 'video_id');
    }
    public function is_like()
    {
        return $this->hasMany('App\LikeVideo', 'video_id');
    }
    public function related_1()
    {
        return $this->beLongsTo('App\Video', 'related_videos_1')->select('id','title','discription','thumbnail','video','pdf','sponser', 'related_videos_1','related_videos_2','related_videos_3','type', 'view', 'ads_text', 'ads_url', 'banner', 'url', 'start_date');
    }
    public function related_2()
    {
        return $this->beLongsTo('App\Video', 'related_videos_2')->select('id','title','discription','thumbnail','video','pdf','sponser', 'related_videos_1','related_videos_2','related_videos_3','type', 'view', 'ads_text', 'ads_url', 'banner', 'url', 'start_date');
    }
    public function related_3()
    {
        return $this->beLongsTo('App\Video', 'related_videos_3')->select('id','title','discription','thumbnail','video','pdf','sponser', 'related_videos_1','related_videos_2','related_videos_3','type', 'view', 'ads_text', 'ads_url', 'banner', 'url', 'start_date');
    }
    public function slider()
    {
        return $this->hasMany('App\Slider', 'video_id');
    }
    public function is_answer()
    {
        return $this->hasMany('App\UserQuestion', 'video_id');
    }
    public function channel()
    {
        return $this->beLongsTo('App\Channel', 'channel_id');
    }
    public function stop_time()
    {
        return $this->hasMany('App\StopVideo', 'video_id', 'id');
    }
    public function videocategoryrelas()
    {
        return $this->hasMany('App\VideoCategoryRela', 'video_id');
    }
    public function views()
    {
        return $this->hasMany('App\ViewVideo', 'video_id');
    }
    public function last_views()
    {
        return $this->hasMany('App\ViewVideo', 'video_id');
    }
    public function count_view()
    {
        return $this->hasMany('App\ViewVideo', 'video_id');
    }
    public function count_view_m()
    {
        return $this->hasMany('App\ViewVideo', 'video_id');
    }
    public function count_view_l()
    {
        return $this->hasMany('App\ViewVideo', 'video_id');
    }
    public function complete()
    {
        return $this->hasMany('App\ViewVideo', 'video_id');
    }
    public function withdrawal()
    {
        return $this->hasMany('App\ViewVideo', 'video_id');
    }
    public function complete_m()
    {
        return $this->hasMany('App\ViewVideo', 'video_id');
    }
    public function withdrawal_m()
    {
        return $this->hasMany('App\ViewVideo', 'video_id');
    }
    public function sum_time()
    {
        return $this->hasMany('App\ViewVideo', 'video_id')->select(['time_view','video_id']);
    }
}
