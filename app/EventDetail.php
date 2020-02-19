<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EventDetail extends Model
{
    //
    protected $table = 'event_details';
    protected $fillable = [
        'event_id','name','content','topic_number','member', 'search_c','member25_first_name','member25_last_name','member25_first_name_search','member25_last_name_search','member25_search_like','member24_first_name','member24_last_name','member24_first_name_search','member24_last_name_search','member24_search_like','member23_first_name','member23_last_name','member23_first_name_search','member23_last_name_search','member23_search_like','member22_first_name','member22_last_name','member22_first_name_search','member22_last_name_search','member22_search_like','member21_first_name','member21_last_name','member21_first_name_search','member21_last_name_search','member21_search_like','member20_first_name','member20_last_name','member20_first_name_search','member20_last_name_search','member20_search_like','member19_first_name','member19_last_name','member19_first_name_search','member19_last_name_search','member19_search_like','member18_first_name','member18_last_name','member18_first_name_search','member18_last_name_search','member18_search_like','member17_first_name','member17_last_name','member17_first_name_search','member17_last_name_search','member17_search_like','member16_first_name','member16_last_name','member16_first_name_search','member16_last_name_search','member16_search_like','member15_first_name','member15_last_name','member15_first_name_search','member15_last_name_search','member15_search_like','member14_first_name','member14_last_name','member14_first_name_search','member14_last_name_search','member14_search_like','member13_first_name','member13_last_name','member13_first_name_search','member13_last_name_search','member13_search_like','member12_first_name','member12_last_name','member12_first_name_search','member12_last_name_search','member12_search_like','member11_first_name','member11_last_name','member11_first_name_search','member11_last_name_search','member11_search_like','member10_first_name','member10_last_name','member10_first_name_search','member10_last_name_search','member10_search_like','member9_first_name','member9_last_name','member9_first_name_search','member9_last_name_search','member9_search_like','member8_first_name','member8_last_name','member8_first_name_search','member8_last_name_search','member8_search_like','member7_first_name','member7_last_name','member7_first_name_search','member7_last_name_search','member7_search_like','member6_first_name','member6_last_name','member6_first_name_search','member6_last_name_search','member6_search_like','member5_first_name','member5_last_name','member5_first_name_search','member5_last_name_search','member5_search_like','member4_first_name','member4_last_name','member4_first_name_search','member4_last_name_search','member4_search_like','member3_first_name','member3_last_name','member3_first_name_search','member3_last_name_search','member3_search_like','member2_first_name','member2_last_name','member2_first_name_search','member2_last_name_search','member2_search_like','member1_first_name','member1_last_name','member1_first_name_search','member1_last_name_search','member1_search_like'
    ];
    public function events()
    {
        return $this->beLongsTo('App\Event', 'event_id');
    }
    
}
