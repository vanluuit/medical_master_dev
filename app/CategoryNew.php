<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CategoryNew extends Model
{
    //
    protected $table = 'category_news';
    protected $fillable = [
        'category_name','nabi','publish'
    ];
}
