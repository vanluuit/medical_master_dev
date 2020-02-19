<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserEvent extends Model
{
    //
    protected $table = 'user_events';
    public function events()
    {
        return $this->beLongsTo('App\Event', 'event_id');
    }
    public function user()
    {
        return $this->beLongsTo('App\User', 'user_id');
    }
    public function device()
    {
        return $this->hasMany('App\Device', 'user_id', 'user_id');
    }
}
