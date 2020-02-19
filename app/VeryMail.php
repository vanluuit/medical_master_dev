<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class VeryMail extends Model
{
    //
    protected $table = 'verymails';
    const UPDATED_AT = null;
     protected $fillable = [
        'email','code'
    ];
}
