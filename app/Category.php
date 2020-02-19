<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB, Log;

class Category extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = 'categories';
    const UPDATED_AT = null;
    protected $fillable = [
        'category', 'code'
    ];
    public function member()
    {
        return $this->hasMany('App\Member', 'category_id', 'id');
    }
    public function banners()
    {
        return $this->hasMany('App\Banner', 'category_id', 'id');
    }
    public function user_category()
    {
        return $this->hasMany('App\UserCategory', 'category_id', 'id');
    }
    
}
