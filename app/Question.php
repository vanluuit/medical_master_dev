<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    //
    protected $table = 'questions';
    protected $fillable = [
        'question', 'video_id'
    ];
    public function answers()
    {
        return $this->hasMany('App\Answer', 'question_id', 'id')->select(['id','question_id','answer']);
    }

    public function UserQuestion()
    {
        return $this->hasMany('App\UserQuestion', 'question_id', 'id');
    }

    
}
