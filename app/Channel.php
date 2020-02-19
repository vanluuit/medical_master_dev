<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Channel extends Model
{
    //
    protected $table = 'channels';
    protected $fillable = [
         'logo', 'title', 'discription', 'sponser', 'is_sponser', 'publish_date', 'nabi', 'category_channel_id'
    ];

    public function association()
    {
        return $this->hasMany('App\ChannelCategory', 'channel_id');
    }
    public function count_like()
    {
        return $this->hasMany('App\LikeChannel', 'channel_id');
    }
    public function is_like()
    {
        return $this->hasMany('App\LikeChannel', 'channel_id');
    }
    public function count_mypage()
    {
        return $this->hasMany('App\UserChannel', 'channel_id');
    }
    public function is_mypage()
    {
        return $this->hasMany('App\UserChannel', 'channel_id');
    }
    public function count_view()
    {
        return $this->hasMany('App\ViewChannel', 'channel_id');
    }
    public function count_view_m()
    {
        return $this->hasMany('App\ViewChannel', 'channel_id');
    }
    public function count_view_l()
    {
        return $this->hasMany('App\ViewChannel', 'channel_id');
    }
    public function contents()
    {
        return $this->hasMany('App\Video', 'channel_id');
    }
}
