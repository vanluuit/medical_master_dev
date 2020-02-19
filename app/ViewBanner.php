<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ViewBanner extends Model
{
    //
    protected $table = 'view_banners';
    const UPDATED_AT = null;
    protected $fillable = [
        'user_id','banner_id'
    ];
}
