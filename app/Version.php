<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB, Log;

class Version extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = 'versions';
    const UPDATED_AT = null;
    protected $fillable = [
        'version', 'status', 'message', 'os'
    ];
}
