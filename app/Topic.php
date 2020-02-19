<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Topic extends Model
{
    //
    protected $table = 'topics';
    protected $fillable = [
        'category_id', 'name', 'start_date', 'start_sunday', 'end_date', 'end_sunday', 'building', 'address', 'map', 'discription', 'url', 'url_text', 'unit'
    ];
}
