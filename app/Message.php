<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Message extends Model
{
    //
    protected $table = 'messages';
    protected $fillable = [
        'title','message', 'type', 'push_date', 'category_id','category_new_id', 'new_id', 'channel_id', 'content_id','user_lists','global', 'push'
    ];
    public function video()
    {
        return $this->beLongsTo('App\Video', 'content_id');
    }
    public function news()
    {
        return $this->beLongsTo('App\Newc', 'new_id');
    }
    public function access_video()
    {
        return $this->hasMany('App\AccessVideo', 'video_id', 'content_id');
    }
    public function access_new()
    {
        return $this->hasMany('App\AccessNew', 'new_id', 'new_id');
    }
    // format date
    public function formatDateTime($field, $format){
      return ($this->attributes[$field]) ? Carbon::createFromFormat('Y-m-d H:i:s',$this->attributes[$field])->format($format) : '';
    } 
}
