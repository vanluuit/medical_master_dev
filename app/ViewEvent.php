<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ViewEvent extends Model
{
    //
    protected $table = 'view_events';
    const UPDATED_AT = null;
    protected $fillable = [
        'user_id','event_id'
    ];
    public function content()
    {
        return $this->beLongsTo('App\Event', 'event_id');
    }
}
