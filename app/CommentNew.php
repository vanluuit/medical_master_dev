<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

class CommentNew extends Model
{
    //
    protected $table = 'comment_news';
    protected $fillable = [
        'user_id','new_id','comment'
    ];
    public function user()
    {
        return $this->beLongsTo('App\User', 'user_id')->select(['id','nickname','pro', 'avatar']);
    }
    public function is_own()
    {
        return $this->beLongsTo('App\User', 'user_id', 'id');
    }
    public function count_like()
    {
        return $this->hasMany('App\LikeComment', 'comment_id');
    }
    public function is_like()
    {
        return $this->hasMany('App\LikeComment', 'comment_id');
    }
}
