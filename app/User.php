<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB, Log;

class User extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'nickname','email','password','firstname','lastname','firstname_k','lastname_k','birthday','career_id','faculty_id', 'hospital_name','roles','sex', 'zip', 'area_hospital', 'city_hospital', 'hospital_name','avatar','pro', 'rss', 'channel', 'content', 'discussion','login_discussion','token','seminar','push_preview', 'status'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password','roles','zip','token','sex'
    ];

    public function member()
    {
        return $this->hasMany('App\Member', 'user_id', 'id')->select(['id','user_id', 'category_id','code']);
    }
    public function career()
    {
        return $this->beLongsTo('App\Career', 'career_id')->select(['id','name']);
    }
    public function faculty()
    {
        return $this->beLongsTo('App\Faculty', 'faculty_id')->select(['id','name']);
    }
    public function association()
    {
        return $this->hasMany('App\UserCategory', 'user_id', 'id');
    }
    public function associationlists()
    {
        return $this->hasMany('App\UserCategory', 'user_id', 'id');
    }
    public function devices()
    {
        return $this->hasMany('App\Device', 'user_id', 'id');
    }
    public function mypage_channel()
    {
        return $this->hasMany('App\UserChannel', 'user_id', 'id');
    }
    public function mypage_disscusion()
    {
        return $this->hasMany('App\UserDiscussion', 'user_id', 'id');
    }
}
