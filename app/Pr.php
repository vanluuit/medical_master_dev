<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Pr extends Model
{
    //
     protected $table = 'prs';
     protected $fillable = [
        'category_id', 'title', 'video_id'
    ];
    public function video()
    {
        return $this->beLongsTo('App\Video', 'video_id', 'id');
    }
    public function category()
    {
        return $this->beLongsTo('App\Category', 'category_id', 'id');
    }
}
