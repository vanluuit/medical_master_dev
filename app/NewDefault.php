<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class NewDefault extends Model
{
    //
    protected $table = 'new_defaults';
    const UPDATED_AT = null;
    protected $fillable = [
        'thumbnail', 'copyright','param'
    ];
}
