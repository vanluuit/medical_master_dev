<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    //
    protected $table = 'settings';
    protected $fillable = [
        'key_s', 'value_s'
    ];
}
