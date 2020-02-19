<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserQuestion extends Model
{
    //
    protected $table = 'user_questions';
    protected $fillable = [
        'user_id', 'question_id', 'answer_id', 'video_id'
    ];
    const UPDATED_AT = null;
    public function user()
    {
        return $this->beLongsTo('App\User', 'user_id')->select(['id','nickname']);
    }
    public function question()
    {
        return $this->beLongsTo('App\Question', 'question_id')->select(['id','question']);
    }
    public function answer()
    {
        return $this->beLongsTo('App\Answer', 'answer_id')->select(['id','answer']);
    }

}
