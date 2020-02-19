<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RssNew extends Model
{
    //
    protected $table = 'rss_news';
    const UPDATED_AT = null;
    protected $fillable = [
        'name', 'url', 'category_new_id','category_id', 'thumbnail','copyright', 'param', 'destroy'
    ];
}
