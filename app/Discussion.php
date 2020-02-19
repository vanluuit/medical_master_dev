<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Discussion extends Model
{
    //
    protected $table = 'discussions';
    protected $fillable = [
        'category_id', 'category_discussion_id', 'user_id', 'title', 'discription', 'image1', 'image2', 'image3', 'new', 'status','password', 'url', 'date_ud'
    ];
    // protected $hidden = [
    //     'password'
    // ];
    public function comments()
    {
        return $this->hasMany('App\CommentDiscussion', 'discussion_id', 'id');
    }
    public function is_mypage()
    {
        return $this->hasMany('App\UserDiscussion', 'discussion_id');
    }
    public function is_like()
    {
        return $this->hasMany('App\LikeDiscussion', 'discussion_id');
    }
    public function count_like()
    {
        return $this->hasMany('App\LikeDiscussion', 'discussion_id');
    }
    public function users()
    {
        return $this->beLongsTo('App\User', 'user_id', 'id')->select(['nickname','avatar','id']);
    }
    public function is_own()
    {
        return $this->beLongsTo('App\User', 'user_id', 'id');
    }
}
