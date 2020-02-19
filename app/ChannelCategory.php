<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ChannelCategory extends Model
{
    //
    protected $table = 'channel_categories';
    protected $fillable = [
        'channel_id','category_id'
    ];
     public function category()
    {
        return $this->beLongsTo('App\Category', 'category_id');
    }
}
