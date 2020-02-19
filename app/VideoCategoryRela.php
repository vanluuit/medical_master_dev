<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class VideoCategoryRela extends Model
{
    //
    protected $table = 'category_video_relas';
    protected $fillable = [
        'video_id', 'category_id', 'nabi'
    ];
    public function videos()
    {
        return $this->beLongsTo('App\Video', 'video_id');
    }
    public function association()
    {
        return $this->beLongsTo('App\Video', 'category_id');
    }
}
