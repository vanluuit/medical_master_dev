<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Rss extends Model
{
    //
    protected $table = 'rss';
     protected $fillable = [
        'category_id','title','url','date','publish','block'
    ];
}
