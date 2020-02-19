<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MemberListMemberPush extends Model
{
    //
    protected $table = 'member_list_member_pushs';
     protected $fillable = [
        'list_member_id','member_id','category_id'
    ];
    public function association()
    {
    	return $this->beLongsTo('App\Member', 'member_id');
    }
}
