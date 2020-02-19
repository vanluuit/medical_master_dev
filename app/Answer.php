<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Answer extends Model
{
    //
    protected $table = 'answers';
    protected $fillable = [
        'question_id', 'answer'
    ];
    // protected $fillable = [
    //     'user_id', 'question_id', 'answer_id', 'video_id'
    // ];
    const UPDATED_AT = null;
}
