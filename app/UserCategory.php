<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserCategory extends Model
{
    //
    protected $table = 'user_categories';
    public $fillable = ['user_id', 'category_id','member_id','status', 'request_date', 'approve_date', 'refuse_date','type'];
    const UPDATED_AT = null;
    public function category()
    {
        return $this->beLongsTo('App\Category', 'category_id', 'id')->select(['id','category']);
    }
    public function member()
    {
        return $this->beLongsTo('App\Member', 'member_id', 'id')->select(['id','code']);
    }
    public function user()
    {
        return $this->beLongsTo('App\User', 'user_id');
    }
    public function membercode()
    {
        return $this->beLongsTo('App\Member', 'category_id', 'id');
    }
}
