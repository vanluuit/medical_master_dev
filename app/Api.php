<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB, Log;

class Api extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = 'api_keys';
    const UPDATED_AT = null;
    protected $fillable = [
        'api_key'
    ];
}
