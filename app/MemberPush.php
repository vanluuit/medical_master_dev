<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MemberPush extends Model
{
    //
    protected $table = 'member_pushs';
    protected $fillable = [
        'category_id','code'
    ];
    public function association()
    {
    	return $this->beLongsTo('App\Category', 'category_id');
    }
}
