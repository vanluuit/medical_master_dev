<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    //
    protected $table = 'events';
    protected $fillable = [
        'seminar_id','category_event_id','theme_event_id','topic_number','name','content','year','month','day','thday', 'start_time_view','end_time_view','start_time','end_time','name_basis','hall','floor','room','preside','member','sponsored', 'search_c','preside1_first_name','preside1_last_name','preside1_first_name_search','preside1_last_name_search','preside1_search_like','preside2_first_name','preside2_last_name','preside2_first_name_search','preside2_last_name_search','preside2_search_like','preside3_first_name','preside3_last_name','preside3_first_name_search','preside3_last_name_search','preside3_search_like','preside4_first_name','preside4_last_name','preside4_first_name_search','preside4_last_name_search','preside4_search_like','preside5_first_name','preside5_last_name','preside5_first_name_search','preside5_last_name_search','preside5_search_like','preside6_first_name','preside6_last_name','preside6_first_name_search','preside6_last_name_search','preside6_search_like','preside7_first_name','preside7_last_name','preside7_first_name_search','preside7_last_name_search','preside7_search_like','preside8_first_name','preside8_last_name','preside8_first_name_search','preside8_last_name_search','preside8_search_like','hall_id'
    ];
    public function count_mypage()
    {
        return $this->hasMany('App\UserEvent', 'event_id', 'id');
    }
    public function is_mypage()
    {
        return $this->hasMany('App\UserEvent', 'event_id', 'id');
    }
    public function user_events()
    {
        return $this->hasMany('App\UserEvent', 'event_id');
    }

    public function category()
    {
        return $this->beLongsTo('App\CategoryEvent', 'category_event_id')->select(['id','name', 'name_search','color']);
    }
    public function theme()
    {
        return $this->beLongsTo('App\ThemeEvent', 'theme_event_id')->select(['id','name']);
    }
    public function hall()
    {
        return $this->beLongsTo('App\Hall', 'hall_id')->select(['id','nabi']);
    }
    public function count_view()
    {
        return $this->hasMany('App\ViewEvent', 'event_id', 'id');
    }
    public function ratings()
    {
        return $this->hasMany('App\UserEvent', 'event_id', 'id');
    }
    public function event_detail()
    {
        return $this->hasMany('App\EventDetail', 'event_id', 'id')->select(['id','event_id','name','topic_number','member','content']);
    }
}
