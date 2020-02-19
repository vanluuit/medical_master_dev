<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ViewChannel extends Model
{
    //
    protected $table = 'view_channels';
    const UPDATED_AT = null;
    protected $fillable = [
        'user_id','channel_id'
    ];
}
