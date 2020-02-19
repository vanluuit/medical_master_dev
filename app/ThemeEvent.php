<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ThemeEvent extends Model
{
    //
    protected $table = 'theme_events';
    protected $fillable = [
         'name'
    ];
    public function events()
    {
        return $this->hasMany('App\Event', 'theme_event_id');
    }
}
