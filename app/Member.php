<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Member extends Model
{
    //
    protected $table = 'member';
     protected $fillable = [
        'category_id','code', 'user_id', 'status'
    ];
    public function association()
    {
    	return $this->beLongsTo('App\Category', 'category_id');
    }
    public function associationu()
    {
        return $this->hasMany('App\UserCategory', 'member_id');
    }
    public function users()
    {
    	return $this->beLongsTo('App\User', 'user_id');
    }
}
