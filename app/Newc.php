<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Newc extends Model
{
    //
    protected $table = 'news';
     protected $fillable = [
        'category_new_id','type','title','content', 'description','media','top','category_id','url','url_check','copyright', 'date', 'block','publish', 'rss_new_id'
    ];
    public function comments()
    {
        return $this->hasMany('App\CommentNew', 'new_id', 'id')->select(['id','user_id', 'new_id','comment', 'created_at']);
    }
    public function count_mypage()
    {
        return $this->hasMany('App\UserNew', 'new_id', 'id');
    }
    public function is_mypage()
    {
        return $this->hasMany('App\UserNew', 'new_id', 'id');
    }
    public function count_like()
    {
        return $this->hasMany('App\LikeNew', 'new_id', 'id');
    }
    public function is_like()
    {
        return $this->hasMany('App\LikeNew', 'new_id', 'id');
    }
    public function category()
    {
        return $this->beLongsTo('App\CategoryNew', 'category_new_id', 'id');
    }
    public function association()
    {
        return $this->beLongsTo('App\Category', 'category_id');
    }
    public function count_view()
    {
        return $this->hasMany('App\ViewNew', 'new_id', 'id');
    }
    public function count_comment()
    {
        return $this->hasMany('App\CommentNew', 'new_id', 'id');
    }
    public function rssmews()
    {
        return $this->beLongsTo('App\RssNew', 'rss_new_id');
    }
}
