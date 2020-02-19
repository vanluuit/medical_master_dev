<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CommentDiscussion extends Model
{
    //
    protected $table = 'comment_discussions';
    const UPDATED_AT = null;
    protected $fillable = [
         'discussion_id', 'user_id', 'parent_id', 'comment', 'image1', 'image2', 'image3', 'cm_file','push'
    ];
    public function parent()
    {
        return $this->beLongsTo('App\CommentDiscussion', 'parent_id');
    }
    public function users()
    {
        return $this->beLongsTo('App\User', 'user_id')->select(['nickname','avatar','id']);
    }
    public function count_report()
    {
        return $this->hasMany('App\CommentDiscussionReport', 'comment_id', 'id');
    }
    public function is_own()
    {
        return $this->beLongsTo('App\User', 'user_id', 'id');
    }
    public function is_like()
    {
        return $this->hasMany('App\LikeCommentDiscussion', 'comment_id');
    }
    public function count_like()
    {
        return $this->hasMany('App\LikeCommentDiscussion', 'comment_id');
    }
}
