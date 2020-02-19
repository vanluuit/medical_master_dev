<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CategoryEvent extends Model
{
    //
    protected $table = 'category_events';
    protected $fillable = [
         'name', 'name_search', 'color'
    ];
    public function events()
    {
        return $this->hasMany('App\Event', 'category_event_id');
    }
}
