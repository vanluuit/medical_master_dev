<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RssUrl extends Model
{
    //
    protected $table = 'rss_urls';
    const UPDATED_AT = null;
    protected $fillable = [
        'name', 'url'
    ];
}
