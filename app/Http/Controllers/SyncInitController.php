<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Crypt;
use Illuminate\Http\Request;
use App\User;
use App\VeryMail;
use UploadedFile;
use Illuminate\Support\Facades\Storage;
use App\Member;
use App\Career;
use App\Faculty;
use App\Category;
use App\UserCategory;
use App\Device;
use App\Province;
use App\Event;
use App\Hall;


use Validator;
use Mail;
use DB;


class SyncInitController extends Controller
{
    public function member()
    {
        // $users = UserCategory::with(['membercode'])->where('category_id', '>', 8)->groupBy('user_id')->get();
        UserCategory::where('category_id', '>', 8)->delete();
        $cate = UserCategory::where('category_id', '>', 0)->get();
        // member::where('category_id', '>', 7)->delete();
        $users = UserCategory::with(['membercode'])->where('category_id', '>', 8)->groupBy('user_id')->get();
        foreach ($cate as $key => $value) {
            Member::where( 'id', $value->member_id )->update([ 'category_id'=>$value->category_id, 'status'=> $value->status]);
        }
        member::where('category_id', '>', 8)->delete();
        // dd($cate[0]);
        echo "ok";
        die();
    }
    public function InitDevice()
    {
        $users = User::with(['devices'])->get();
        foreach ($users as $key => $value) {
            if(!count($value->devices)) {
                echo $value->id." ".$value->email."<br />";
            }
            
        }
        die();
    }
    public function InitHall(){
        $events = Event::all();
        foreach ($events as $key => $value) {
            $hall_id = 0;
            if($value->hall != "") {
                $hall = Hall::where('name', $value->hall)->where('seminar_id', $value->seminar_id)->first();
                if($hall) {
                    $hall_id = $hall->id;
                }else{
                    $hall_id = Hall::insertGetId([
                            'seminar_id' => $value->seminar_id,
                            'name' => $value->hall,
                            'nabi' => 999999999
                        ]);
                }
                // dd($hall_id );
            }
            Event::find($value->id)->update(['hall_id'=>$hall_id]);
        }
        dd($events);
    }
}
