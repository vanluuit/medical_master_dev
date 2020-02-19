<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ListMemberPush extends Model
{
    //
    protected $table = 'list_member_pushs';
     protected $fillable = [
        'name','category_id'
    ];
    public function memberList()
    {
    	return $this->hasMany('App\MemberListMemberPush', 'list_member_id');
    }
}
