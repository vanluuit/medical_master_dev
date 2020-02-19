<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CommentDiscussionReport extends Model
{
    //
    protected $table = 'comment_discussion_report';
    const UPDATED_AT = null;
    protected $fillable = [
         'discussion_id', 'comment_id'
    ];
}
