<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Seminar;
use App\Category;
use App\Event;
use App\UserEvent;
use App\Banner;
use App\Video;
use App\Setting;
use App\User;
use Validator;
use Illuminate\Support\Facades\Storage;
class SeminarController extends Controller
{
    //api

    public function api_by_association(Request $request)
    {
        $user_id = get_user_id($request);

        $category_id = $request->association_id;
        // $channels = Channel::with(['association'])->get();
        $callback = function($query)use($category_id) {
            $query->where('category_id', $category_id);
        };
        $categories = Category::whereHas('user_category', $callback)->with(['user_category' => $callback])->where('publish', 0)->pluck('id');

        $seminar = Seminar::select('*')
            ->whereIn('category_id', $categories)
            ->where('category_id', $category_id)
            ->where('publish', 1)
            // ->where('start_date', '<=' , date('Y-m-d'))
            // ->where('end_date', '>=' , date('Y-m-d'))
            ->first();

        
        if($seminar) {
            list($width, $height) = @getimagesize('/var/www/html'.$seminar->banner);
            $seminar->banner_w = @$width;
            $seminar->banner_h = @$height;
            list($width, $height) = @getimagesize('/var/www/html'.$seminar->image);
            $seminar->image_w = @$width;
            $seminar->image_h = @$height;

            $data["statusCode"] = 0;
            $data["message"] = "";
            $data['data'] = $seminar;  
        }else{
            $data["statusCode"] = 0;
            $data["message"] = "";
            $data['data'] = $seminar;
        }
        
        
        return response()->json($data);
    }

    public function api_banner_ads(Request $request)
    {
        $user_id = get_user_id($request);
        $push_user = User::where('id', $user_id)->first();
        $category_id = $request->association_id;
        // $channels = Channel::with(['association'])->get();
        $callback = function($query)use($user_id) {
            $query->where('user_id', $user_id);
        };
        $categories = Category::whereHas('user_category', $callback)->with(['user_category' => $callback])->where('publish', 0)->pluck('id');
        $banner_number = Setting::where('key_s','banner_number')->first();
        if($banner_number) {
            $number = $banner_number->value_s;
        }else{
            $number = 0;
        }   
        $data_ads = [];
        if($number > 0) {
        	for ($i=1; $i <=$number ; $i++) { 
        		$ads=[];
        		$ads = Banner::select('id', 'image','video_id', 'type', 'url')
		            ->whereIn('category_id', $categories)
		            ->where('category_id', $category_id)
		            ->where('location', $i);
		        $ads = $ads->where(function ($queryt) use($push_user){
		                $queryt->where(function ($queryd) use($push_user){
		                    $queryd->where('type', 1)
		                        ->whereHas('video',function($qr)use($push_user){
		                        $qr->where('start_date','<', date('Y-m-d H:i:s'))
		                            ->where(function ($query) {
		                                $query->where('end_date', '>', date('Y-m-d H:i:s'))
		                                    ->orWhere('end_date', '=', '0000-00-00 00:00:00');
		                            });
                                if($push_user->career_id == 20) {
                                    $qr = $qr->whereHas('channel', function($qrs){
                                        $qrs->where('is_sponser', 0);
                                    });
                                }
		                    });                
		                })->orWhere(function ($queryd) {
		                    $queryd->where('type', 2)
		                    ->where('start_date','<', date('Y-m-d H:i:s'))
		                    ->where(function ($query) {
		                        $query->where('end_date', '>', date('Y-m-d H:i:s'))
		                            ->orWhere('end_date', '=', '0000-00-00 00:00:00');
		                    });
		                });
		            });
		        $ads = $ads->where('publish', 1)
		            ->get();
		        if(count($ads)) {
		            $ads = @$ads[rand(0,count($ads)-1)];
		            if($ads->type==1) {
		                $videos = $this->get_video_by_video_id($user_id, $ads->video_id, $ads);
		                $ads->video = $videos;
		            }
		            $data_ads[] = $ads;  
		        }
        	}
        }
        
        foreach ($data_ads as $key_a => $value_a) {
            list($width, $height) = getimagesize('/var/www/html'.$value_a->image);
            $value_a->width = $width;
            $value_a->height = $height;
        }
        if(count($data_ads)) {
            $data["statusCode"] = 0;
            $data["message"] = "";
            $data['data'] = $data_ads;  
        }else{
            $data["statusCode"] = 0;
            $data["message"] = "";
            $data['data'] = $ads;
        }
        
        
        return response()->json($data);
    }

    public static function get_video_by_video_id($user_id, $video_id, $ads){
        $videos = Video::select('id','title','discription','thumbnail','video','pdf','sponser','sponser', 'related_videos_1','related_videos_2','related_videos_3','type', 'view', 'ads_text', 'ads_url', 'ads_text_2', 'ads_url_2')
            ->with(['slider','related_1'=>function($qr1)use($user_id, $video_id){
                $qr1->withCount(['count_like', 'is_like'=>function($qr1)use($user_id){
                        $qr1->where('user_id', $user_id);
                    },'is_answer'=>function($qr2)use($user_id, $video_id){
                    $qr2->where('user_id', $user_id)->where('video_id', $video_id);
                }]);
            },'related_2'=>function($qr1)use($user_id, $video_id){
                $qr1->withCount(['count_like', 'is_like'=>function($qr1)use($user_id, $video_id){
                        $qr1->where('user_id', $user_id);
                    },'is_answer'=>function($qr2)use($user_id, $video_id){
                    $qr2->where('user_id', $user_id)->where('video_id', $video_id);
                }]);
            },'related_3'=>function($qr1)use($user_id, $video_id){
                $qr1->withCount(['count_like', 'is_like'=>function($qr1)use($user_id){
                        $qr1->where('user_id', $user_id);
                    },'is_answer'=>function($qr2)use($user_id, $video_id){
                    $qr2->where('user_id', $user_id)->where('video_id', $video_id);
                }]);
            }])
            ->where('publish',0)
            ->withCount(['count_like', 'is_like'=>function($qr1)use($user_id){
                        $qr1->where('user_id', $user_id);
                    },'is_answer'=>function($qr2)use($user_id, $video_id){
                    $qr2->where('user_id', $user_id)->where('video_id', $video_id);
                }])
            ->where('id',$ads->video_id)
            ->first();
            // return response()->json($videos);
            if(@$videos->is_answer_count > 0) {
                $videos->is_answer_count = 1;
            }
            $ralated = [];
            if(@$videos->related_1){
                if(@$videos->related_1->is_answer_count > 0) {
                    $videos->related_1->is_answer_count = 1;
                }
                $ralated[] = $videos->related_1;
            }
            if(@$videos->related_2){
                if(@$videos->related_2->is_answer_count > 0) {
                    $videos->related_2->is_answer_count = 1;
                }
                $ralated[] = $videos->related_2;
            }
            if(@$videos->related_3){
                if(@$videos->related_3->is_answer_count > 0) {
                    $videos->related_3->is_answer_count = 1;
                }
                $ralated[] = $videos->related_3;
            }
            // return response()->json($ralated);
            @$videos->ralated =  $ralated;
            unset($videos->related_1);
            unset($videos->related_2);
            unset($videos->related_3);
            return $videos;
    }

    public function api_mypage_list(Request $request)
    {
        $user_id = get_user_id($request);

        $callback = function($query)use($user_id) {
            $query->where('user_id', $user_id);
        };
        $categories = Category::whereHas('user_category', $callback)->with(['user_category' => $callback])->where('publish', 0)->pluck('id');

        $seminar = Seminar::select('id', 'title', 'start_date', 'end_date')
            ->whereHas('events', function($qr)use($user_id){
                $qr->whereHas('user_events', function($qr2)use($user_id){
                    $qr2->where('user_id',$user_id);
                });
            })
            ->get();
        if($seminar) {
            $data["statusCode"] = 0;
            $data["message"] = "";
            $data['data'] = $seminar;  
        }else{
            $data["statusCode"] = 0;
            $data["message"] = "";
            $data['data'] = $seminar;
        }
        
        
        return response()->json($data);
    }

    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $requuest)
    {
        //

        $seminars = Seminar::where('id','>',0);
        if($requuest->category_id) {
            $seminars = $seminars->where('category_id', $requuest->category_id);
        }
        $seminars = $seminars->get();
        // $seminars = Seminar::where('id','>',0);
        // dd($seminars);
        return view('seminars.index', compact(['seminars']));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        $seminars = Seminar::where('id','>',0)->pluck('category_id');
        // $categories = Category::whereNotIn('id', $seminars)->pluck('category','id');
        $categories = Category::pluck('category','id');
        return view('seminars.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        $validator = Validator::make(
            $request->all(), 
            [
                'title' => 'required',            ],
            [
                'title.required' => 'メールアドレスが間違っています。',
            ]
        );
        if(strtotime($request->start_date) > strtotime($request->end_date)) {
            $validator->after(function ($validator) {
                 $validator->errors()->add('end_date.format', '終了日が開始日より大きい');
            });
        }
        if ($validator->fails()) {
            return redirect(route('seminars.create'))
                        ->withErrors($validator)
                        ->withInput();
        }
        $data = $request->all();
        $data['time'] = json_encode($data['time']);
        // dd($data);
        unset($data['_token']);
        unset($data['image']);
        unset($data['banner']);
        unset($data['map_image']);
        unset($data['map']);
        unset($data['event_excel']);
        $rs = Seminar::insertGetId($data);
        if($rs) {
            if($request->banner){
                $path = '/storage/app/'.@$request->file('banner')->store('seminar');
                $semi = Seminar::find($rs);
                $semi->banner = $path;
                $semi->save();
            }
            if($request->map_image){
                $path = '/storage/app/'.@$request->file('map_image')->store('seminar');
                $semi = Seminar::find($rs);
                $semi->map_image = $path;
                $semi->save();
            }
            if($request->image){
                $path = '/storage/app/'.@$request->file('image')->store('seminar');
                $semi = Seminar::find($rs);
                $semi->image = $path;
                $semi->save();
            }
            if($request->map){
                $path = '/storage/app/'.@$request->file('map')->store('seminar');
                $semi = Seminar::find($rs);
                $semi->map = $path;
                $semi->save();
            }
            return redirect(route('seminars.index'));
        }else{
            return back();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
        $seminars = Seminar::where('id','>',0)->pluck('category_id');
        $seminar = Seminar::find($id);
        // $categories = Category::where(function ($query)use($seminars, $seminar)  {
        //         $query->orWhereNotIn('id', $seminars)
        //             ->orWhere('id',$seminar->category_id);
        //     })->pluck('category','id');
        $categories = Category::pluck('category','id');
        return view('seminars.edit', compact(['categories','seminar']));
        

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make(
            $request->all(), 
            [
                'title' => 'required',          
            ],
            [
                'title.required' => 'メールアドレスが間違っています。',
            ]
        );
        if(strtotime($request->start_date) > strtotime($request->end_date)) {
            $validator->after(function ($validator) {
                 $validator->errors()->add('end_date.format', '終了日が開始日より大きい');
            });
        }
        if ($validator->fails()) {
            return back()
                        ->withErrors($validator)
                        ->withInput();
        }
        $data = $request->all();
        $data['time'] = json_encode($data['time']);
        unset($data['_token']);
        unset($data['_method']);
        unset($data['image']);
        unset($data['banner']);
        unset($data['map_image']);
        unset($data['map']);
        unset($data['event_excel']);
        unset($data['delete_image']);
        $rs = Seminar::find($id)->update($data);
        if($rs) {
            if($request->banner){
                $semi = Seminar::find($id);
                $banner_u = $semi->banner;
                $banner_u = str_replace('/storage/app/', '', $banner_u);
                $path = '/storage/app/'.@$request->file('banner')->store('seminar');
                $semi->banner = $path;
                $semi->save();
                Storage::delete($banner_u);
            }
            if($request->map_image){
                $semi = Seminar::find($id);
                $map_image_u = $semi->map_image;
                $map_image_u = str_replace('/storage/app/', '', $map_image_u);
                $path = '/storage/app/'.@$request->file('map_image')->store('seminar');
                $semi->map_image = $path;
                $semi->save();
                Storage::delete($map_image_u);
            }
            if($request->delete_image){
                $semi = Seminar::find($id);
                $image_u = $semi->image;
                $image_u = str_replace('/storage/app/', '', $image_u);
                $semi->image = '';
                $semi->save();
                Storage::delete($image_u);
            }else{
                if($request->image){
                    $semi = Seminar::find($id);
                    $image_u = $semi->image;
                    $image_u = str_replace('/storage/app/', '', $image_u);
                    $path = '/storage/app/'.@$request->file('image')->store('seminar');
                    $semi->image = $path;
                    $semi->save();
                    Storage::delete($image_u);
                }
            }
            
            if($request->map){
                $semi = Seminar::find($id);
                $map_u = $semi->map;
                $map_u = str_replace('/storage/app/', '', $map_u);
                $path = '/storage/app/'.@$request->file('map')->store('seminar');
                $semi->map = $path;
                $semi->save();
                Storage::delete($map_u);
            }
            return redirect(route('seminars.index'));
        }else{
            return back();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        $seminar = Seminar::find($id);
        if($seminar) {
            if($seminar->banner){
                $banner_u = $seminar->banner;
                $banner_u = str_replace('/storage/app/', '', $banner_u);
                Storage::delete($banner_u);
            }
            if($seminar->map_image){
                $map_image_u = $seminar->map_image;
                $map_image_u = str_replace('/storage/app/', '', $map_image_u);
                Storage::delete($map_image_u);
            }
            if($seminar->image){
                $image_u = $seminar->image;
                $image_u = str_replace('/storage/app/', '', $image_u);
                Storage::delete($image_u);
            }
            if($seminar->map){
                $map_u = $seminar->map;
                $map_u = str_replace('/storage/app/', '', $map_u);
                Storage::delete($map_u);
            }
            $eventss = Event::where('seminar_id', $id)->pluck('id');
            UserEvent::whereIn('event_id', $eventss)->delete();
            Event::where('seminar_id', $id)->delete();
            Seminar::destroy($id);
        }
        return redirect(route('seminars.index'));
    }
     /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function ajaxshow(Request $request)
    {
        // Newc::where('id','>',0)->update(['top'=>0]);
        $seminar = Seminar::find($request->id);
        Seminar::where('category_id', $seminar->category_id)->update(['publish'=> 0]);
        $seminar->publish = $request->publish;
        // $seminar->date = date('Y-m-d H:i:s');
        $seminar->save();
        echo $seminar->publish; die();
    }
    public function ajaxlink(Request $request)
    {
        // Newc::where('id','>',0)->update(['top'=>0]);
        $seminar = Seminar::find($request->id);
        $seminar->link = $request->link;
        // $seminar->date = date('Y-m-d H:i:s');
        $seminar->save();
        echo $seminar->link; die();
    }
}
