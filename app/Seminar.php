<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Seminar extends Model
{
    //
    protected $table = 'seminars';
    const UPDATED_AT = null;
    protected $fillable = [
        'category_id', 'banner', 'image', 'title', 'start_date', 'end_date', 'map', 'map_image', 'website', 'publish','link','lean','time'
    ];

    public function count_mypage()
    {
        return $this->hasMany('App\UserSeminar', 'seminar_id');
    }
    public function is_mypage()
    {
        return $this->hasMany('App\UserSeminar', 'seminar_id');
    }

    public function events()
    {
        return $this->hasMany('App\Event', 'seminar_id');
    }
}
