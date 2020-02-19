<?php

namespace App\Modules\Push\Controllers;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\User;
use App\CategoryNew;
use App\Newc;
use App\Category;
use App\Seminar;
use App\Message;
use App\Device;
use App\Rss;
use App\Channel;
use App\Video;
use App\Discussion;
use App\CommentDiscussion;
use App\AccessNew;
use App\AccessVideo;
use App\Career;
use App\Province;
use App\Member;
use App\UserCategory;
use App\PushUser;
use App\ListMemberPush;
use App\MemberListMemberPush;

use Carbon\Carbon;


use Validator;



class NotificationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function api_list(Request $request)
    {
        //
        $user_id = get_user_id($request);
        $user = User::find($user_id);
        $category = Category::select('id','category', 'created_at')->whereDate('created_at','>', $user->time_out)->orderBy('id', 'DESC')->get();
        $messages = Message::where('push',1)->where('push_date','>', $user->time_out)->orderBy('id', 'DESC')->get();
        $str = [];
        if(count($category)){
            foreach ($category as $key => $value) {
                $str[] = date('Y-m-d', strtotime($value->created_at))." ". date('H:i', strtotime($value->created_at)).' '.$value->category.'が新たに登録可能となりました。';
            }
        }
        if(count($messages)){
            foreach ($messages as $key => $value) {
                $str[] = date('Y-m-d', strtotime($value->push_date))." ". date('H:i', strtotime($value->push_date)).' '.$value->message;
            }
        }
        
        if(count($str)) {
            $data["statusCode"] = 0;
            $data["message"] = "";
            $data['data'] = $str;  
        }else{
            $data["statusCode"] = 0;
            $data["message"] = "";
            $data['data'] = $str;
        }
         return response()->json($data);
    }

    public function api_list_all(Request $request)
    {
        //
        $user_id = get_user_id($request);
        $user = User::find($user_id);
        $callback = function($query)use($user_id) {
            $query->where('user_id', $user_id);
        };
        $categories = Category::whereHas('user_category', $callback)->with(['user_category' => $callback])->select('id','category')->where('publish', 0)->get();
        $dt['quick']['RSS']['association'] = $this->rss_association($categories, $user);
        $dt['quick']['Seminar']['association'] = $this->seminar_association($categories, $user);
        // $dt['TVpro']['Channel']['association'] = $this->channel_association($categories, $user);
        $dt['TVpro']['Channel_Content']['association'] = $this->content_association($categories, $user);
        $dt['Mypage']['Discussion']['association'] = $this->discussion_association($categories, $user);

        // if($categories) {
        $data["statusCode"] = 0;
        $data["message"] = "";
        $data['data'] = $dt;  
        // }
         return response()->json($data);
    }
    public static function rss_association($categories, $user){
        $rss_ur = json_decode($user->rss);
        if(empty($rss_ur)) $rss_ur = [];
        $rss_association = [];
        foreach ($categories as $keycate => $category) {
            $rss = [];
            $rss = Rss::select('id','title','url','date','category_id')
            // ->whereIn('category_id', $categories)
            ->where('category_id', $category->id)
            ->orderBy('date','DESC')
            ->take(3)
            ->get('id');
            if(count($rss)) {
                foreach ($rss as $k_r => $v_r) {
                    if($ke = @array_search($v_r->id, $rss_ur) !== false){
                        $rss_association[] = $category->id;
                        break;
                    }
                }
            }
            
        }
        return $rss_association;
    }
    public static function seminar_association($categories, $user){
        $seminar_ur = json_decode($user->seminar);
        if(empty($seminar_ur)) $seminar_ur = [];
        $message = Message::where('type', 5)->whereIn('id', $seminar_ur)->groupBy('category_id')->pluck('category_id')->toArray();
        return $message;
    }
    public static function channel_association($categories, $user){
        $channel_ur = json_decode($user->channel);
        if(empty($channel_ur)) $channel_ur = [];
        $channel_association = [];
        foreach ($categories as $keycate => $category) {
            $channels = [];
            $channels = Channel::select('id','logo','title','discription','sponser','publish_date', 'created_at')
            ->where('publish_date','<',date('Y-m-d H:i:s'))
            ->where('publish',0)
            ->whereHas('association', function($query)use($category) {
                $query->where('category_id', $category->id);
            })
            // ->where('category_channel_id',@$request->category_id)
            ->orderBy('nabi' , 'DESC')
            ->get();
            if(count($channels)) {
                foreach ($channels as $k_r => $v_r) {
                    if($ke = @array_search($v_r->id, $channel_ur) !== false){
                        $channel_association[] = $category->id;
                        break;
                    }
                }
            }
            
        }
        return $channel_association;
    }
    public static function content_association($categories, $user){
        $content_ur = json_decode($user->content);
        $channel_ur = json_decode($user->channel);
        if(empty($content_ur)) $content_ur = [];
        if(empty($channel_ur)) $channel_ur = [];
        $content_association = [];
        foreach ($categories as $keycate => $category) {
            $channels = [];
            $channels = Channel::select('id','logo','title','discription','sponser','publish_date', 'created_at')
            ->where('publish_date','<',date('Y-m-d H:i:s'))
            ->where('publish',0)
            ->whereHas('association', function($query)use($category) {
                $query->where('category_id', $category->id);
            })
            // ->where('category_channel_id',@$request->category_id)
            ->orderBy('nabi' , 'DESC')
            ->get();
            
            foreach ($channels as $keycn => $channel) {
                if($ke = @array_search($channel->id, $channel_ur) !== false){
                    $content_association[] = $category->id;
                    break;
                }
                $videos = Video::select('id','title')
                ->where('channel_id',$channel->id)
                ->where('publish',0)
                ->whereDate('start_date','<', date('Y-m-d H:i:s'))
                ->orderBy('start_date' , 'DESC')
                ->take(10)
                ->get();
                if(count($videos)) {
                    foreach ($videos as $k_r => $v_r) {
                        if($ke = @array_search($v_r->id, $content_ur) !== false){
                            $content_association[] = $category->id;
                            // $breakf = 1;
                            break;
                        }
                    }
                }
                // if($breakf==1) break;
            }
        }
        return $content_association;
    }
    public static function discussion_association($categories, $user){
        $discussion_ur = json_decode($user->discussion);
        if(empty($discussion_ur)) $discussion_ur = [];
        $discussion_association = [];
        foreach ($categories as $keycate => $category) {
            $discussions = [];
            $breaks = 0;
            for ($i=1; $i < 5; $i++) { 
                $discussions = Discussion::select('id','title', 'discription', 'image1', 'image2', 'image3','created_at')
                // ->whereIn('category_id', $categories)
                ->where('category_id', $category->id)
                ->where('category_discussion_id', $i)
                ->whereHas('is_mypage', function($qrh)use($user){
                    $qrh->where('user_id', $user->id);
                })
                ->take(10)
                ->get();
                if(count($discussions)) {
                    foreach ($discussions as $k_r => $v_r) {
                        if($ke = @array_search($v_r->id, $discussion_ur) !== false){
                            $discussion_association[] = $category->id;
                            $breaks = 1;
                            break;
                        }
                    }
                }
                if($breaks==1){
                    break;
                }
            }
            
            
        }
        return $discussion_association;
    }
    public function api_rss_read(Request $request)
    {
        $user_id = get_user_id($request);
        $push_user = User::where('id', $user_id)->first();
        if($push_user){
            if($push_user->rss) {
                $rss_p = json_decode($push_user->rss);
            }else{
                $rss_p = [];
            }
        }else{
            $rss_p = [];
        }
        if (($key = array_search($request->id, $rss_p)) !== false) {
            unset($rss_p[$key]);
            $datarss = ['rss'=>json_encode(array_merge($rss_p))];
            User::where('id', $user_id)->update($datarss);
        }

        $callback = function($query)use($user_id) {
            $query->where('user_id', $user_id);
        };
        $categories = Category::whereHas('user_category', $callback)->with(['user_category' => $callback])->select('id','category')->where('publish', 0)->get();

        $rss_association = [];
        $push_user = User::where('id', $user_id)->first();
        $rss_association = $this->rss_association($categories, $push_user);
        $data["statusCode"] = 0;
        $data["message"] = "";
        $data["data"] = $rss_association;
        return response()->json($data);
    }

    public function api_event_read(Request $request)
    {
        $user_id = get_user_id($request);
        $push_user = User::where('id', $user_id)->first();
        if($push_user){
            if($push_user->seminar) {
                $seminar_p = json_decode($push_user->seminar);
            }else{
                $seminar_p = [];
            }
        }else{
            $seminar_p = [];
        }
        if (($key = array_search($request->id, $seminar_p)) !== false) {
            unset($seminar_p[$key]);
            $dataseminar = ['seminar'=>json_encode(array_merge($seminar_p))];
            User::where('id', $user_id)->update($dataseminar);
        }

        // $callback = function($query)use($user_id) {
        //     $query->where('user_id', $user_id);
        // };
        // $categories = Category::whereHas('user_category', $callback)->with(['user_category' => $callback])->select('id','category')->where('publish', 0)->get();

        // $rss_association = [];
        // $push_user = User::where('id', $user_id)->first();
        // $rss_association = $this->rss_association($categories, $push_user);
        $data["statusCode"] = 0;
        $data["message"] = "";
        // $data["data"] = $rss_association;
        return response()->json($data);
    }

    public function api_channel_read(Request $request)
    {
        $user_id = get_user_id($request);
        $push_user = User::where('id', $user_id)->first();
        if($push_user){
            if($push_user->channel) {
                $channel_p = json_decode($push_user->channel);
            }else{
                $channel_p = [];
            }
        }else{
            $channel_p = [];
        }
        if (($key = array_search($request->id, $channel_p)) !== false) {
            unset($channel_p[$key]);
            $datachannel = ['channel'=>json_encode(array_merge($channel_p))];
            User::where('id', $user_id)->update($datachannel);
        }

        $callback = function($query)use($user_id) {
            $query->where('user_id', $user_id);
        };
        $categories = Category::whereHas('user_category', $callback)->with(['user_category' => $callback])->select('id','category')->where('publish', 0)->get();
        $channel_association = [];
        $push_user = User::where('id', $user_id)->first();
        $channel_association = $this->content_association($categories, $push_user);
        $data["statusCode"] = 0;
        $data["message"] = "";
        $data["data"] = $channel_association;
        return response()->json($data);
    }
    public function api_content_read(Request $request)
    {
        $user_id = get_user_id($request);
        $push_user = User::where('id', $user_id)->first();
        if($push_user){
            if($push_user->content) {
                $content_p = json_decode($push_user->content);
            }else{
                $content_p = [];
            }
        }else{
            $content_p = [];
        }
        if (($key = array_search($request->id, $content_p)) !== false) {
            unset($content_p[$key]);
            $datacontent = ['content'=>json_encode(array_merge($content_p))];
            User::where('id', $user_id)->update($datacontent);
        }

        $callback = function($query)use($user_id) {
            $query->where('user_id', $user_id);
        };
        $categories = Category::whereHas('user_category', $callback)->with(['user_category' => $callback])->select('id','category')->where('publish', 0)->get();
        $content_association = [];
        $push_user = User::where('id', $user_id)->first();
        $content_association = $this->content_association($categories, $push_user);
        $data["statusCode"] = 0;
        $data["message"] = "";
        $data["data"] = $content_association;
        return response()->json($data);
    }
    public function api_discussion_read(Request $request)
    {
        $user_id = get_user_id($request);
        $push_user = User::where('id', $user_id)->first();
        if($push_user){
            if($push_user->discussion) {
                $discussion_p = json_decode($push_user->discussion);
            }else{
                $discussion_p = [];
            }
        }else{
            $discussion_p = [];
        }
        if (($key = array_search($request->id, $discussion_p)) !== false) {
            unset($discussion_p[$key]);
            $datadiscussion = ['discussion'=>json_encode(array_merge($discussion_p))];
            User::where('id', $user_id)->update($datadiscussion);
        }

        $callback = function($query)use($user_id) {
            $query->where('user_id', $user_id);
        };
        $categories = Category::whereHas('user_category', $callback)->with(['user_category' => $callback])->select('id','category')->where('publish', 0)->get();
        $content_association = [];
        $push_user = User::where('id', $user_id)->first();
        $content_association = $this->discussion_association($categories, $push_user);
        $data["statusCode"] = 0;
        $data["message"] = "";
        $data["data"] = $content_association;
        return response()->json($data);
    }

    public function notification_seminar_count(Request $request)
    {
        $user_id = get_user_id($request);
        $push_user = User::where('id', $user_id)->first();
        if($push_user){
            if($push_user->seminar) {
                $seminar_p = json_decode($push_user->seminar);
            }else{
                $seminar_p = [];
            }
        }else{
            $seminar_p = [];
        }
        $count = Message::whereIn('id',  $seminar_p)->where('category_id', $request->association_id)->where('type', 5)->count();
        $data["statusCode"] = 0;
        $data["message"] = "";
        $data["data"] = ['count'=>$count];
        return response()->json($data);
    }
    public function notification_seminar_list(Request $request)
    {
        $user_id = get_user_id($request);
        $push_user = User::where('id', $user_id)->first();
        if($push_user){
            if($push_user->seminar) {
                $seminar_p = json_decode($push_user->seminar);
            }else{
                $seminar_p = [];
            }
        }else{
            $seminar_p = [];
        }
        $seminar_id = Seminar::where('category_id', $request->association_id)->where('publish', 1)->first()->id;
        $notification = Message::where('type', 5)
        ->where('category_id', $request->association_id)
        ->where('seminar_id', $seminar_id)
        ->orderBy('created_at', 'DESC')
        ->paginate(10000);
        foreach ($notification as $key => $value) {
            if(in_array($value->id, $seminar_p)) $value->unread = 1;
            else $value->unread = 0;
        }
        $data["statusCode"] = 0;
        $data["message"] = "";
        $data["data"] = $notification;
        return response()->json($data);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //
        $user_id = session('user')['id'];
        $category_id = PushUser::find($user_id)->category_id;
        $tmp = 'Push::notification.index';
        $messages = Message::where('category_id', $category_id);
        if($request->type){
            if($request->type == 2) {
                $messages = $messages->with(['video'])->withCount('access_video');
                $tmp = 'Push::notification.access_video';
            }
            if($request->type == 3) {
                $messages = $messages->with(['news'])->withCount('access_new');
                $tmp = 'Push::notification.access_new';
            }
            $messages = $messages->where('type', $request->type);
        }
        $messages = $messages->orderBy('id', 'DESC')->paginate(20);
        // dd($messages);
        return view($tmp, compact(['messages']));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        //
        $user_id = session('user')['id'];
        $category_id = PushUser::find($user_id)->category_id;
        $cate = Category::pluck('category','id')->toArray();
        array_unshift($cate, '全ての学会');
        $careers_list_id = User::whereHas('association', function($qr)use($category_id){
                $qr->where('category_id', $category_id);
            })->groupBy('career_id')->pluck('career_id')->toArray();
        $careers =  Career::whereIn('id', $careers_list_id)->pluck('name','id')->toArray();
        // dd($careers);
        $provinces_list_id = User::whereHas('association', function($qr)use($category_id){
                $qr->where('category_id', $category_id);
            })->groupBy('area_hospital')->pluck('area_hospital')->toArray();
        $provinces =  Province::whereIn('province_name', $provinces_list_id)->pluck('province_name', 'province_name')->toArray();

        $member_id = UserCategory::where('category_id', $category_id)->where("status", 1)->groupBy('member_id')->pluck('member_id')->toArray();
        $members = Member::whereIn('id', $member_id )->pluck('code','id')->toArray();

        $users = User::whereHas('association', function($qr)use($category_id){
                $qr->where('category_id', $category_id);
            })->get();

        $ListMemberPush = ListMemberPush::where('category_id', $category_id)->pluck('name','id')->toArray();
        
        return view('Push::notification.create', compact(['cate','careers', 'provinces', 'users','members','ListMemberPush']));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function PushTVpro(Request $request)
    {
        //
        $user_id = session('user')['id'];
        $category_id = PushUser::find($user_id)->category_id;
        $cate = Category::pluck('category','id')->toArray();
        array_unshift($cate, '全ての学会');
        $channel_id = Channel::whereHas('association', function($qr)use($category_id){
                $qr->where('category_id', $category_id);
            })->where('is_sponser',0)->first();
        $channel = Channel::whereHas('association', function($qr)use($category_id){
                $qr->where('category_id', $category_id);
            })->where('is_sponser',0)->pluck('title','id')->toArray();
        $videos = video::where('channel_id', $channel_id->id)->pluck('title', 'id')->toArray();

        $careers_list_id = User::whereHas('association', function($qr)use($category_id){
                $qr->where('category_id', $category_id);
            })->groupBy('career_id')->pluck('career_id')->toArray();
        $careers =  Career::whereIn('id', $careers_list_id)->pluck('name','id')->toArray();
        // dd($careers);
        $provinces_list_id = User::whereHas('association', function($qr)use($category_id){
                $qr->where('category_id', $category_id);
            })->groupBy('area_hospital')->pluck('area_hospital')->toArray();
        $provinces =  Province::whereIn('province_name', $provinces_list_id)->pluck('province_name', 'province_name')->toArray();

        $member_id = UserCategory::where("category_id", $category_id)->where("status", 1)->groupBy('member_id')->pluck('member_id')->toArray();
        $members = Member::whereIn('id', $member_id )->pluck('code','id')->toArray();

        $users = User::whereHas('association', function($qr)use($category_id){
                $qr->where('category_id', $category_id);
            })->get();
        $ListMemberPush = ListMemberPush::where('category_id', $category_id)->pluck('name','id')->toArray();
        return view('Push::notification.tvpro', compact(['cate', 'channel','videos','careers', 'provinces', 'users','members','ListMemberPush']));
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
                'title' => 'required',
                'message' => 'required',            ],
            [
                'title.required' => 'タイトルが間違っています。',
                'message.required' => 'messageが間違っています。',
            ]
        );
        if ($validator->fails()) {
            
            return redirect(route('push.notification.create'))
                        ->withErrors($validator)
                        ->withInput();
        }
        $user_id = session('user')['id'];
        $category_id = PushUser::find($user_id)->category_id;
        $seminar_id = 0;
        if($request->type == 5) {
            $seminar = Seminar::where('category_id', $category_id)->first();
            if(!$seminar){
                $validator->after(function ($validator) {
                    $validator->errors()->add('seminar.required', 'Please select a seminar with seminars');
                });
            }
            $seminar_id = Seminar::where('category_id', $category_id)->where('publish', 1)->first();

        }
        // $data = $request->all();
        $data['title'] = $request->title;
        $data['message'] = $request->message;
        $data['type'] = $request->type;
        $data['push'] = $request->push;
        $data['category_id'] = $category_id;
        $data['global'] = $request->global;

        // $data['category_id'] = $request->category_id;
        ($request->channel_id) ? $data['channel_id'] = $request->channel_id : $data['channel_id'] = 0;
        ($request->content_id) ? $data['content_id'] = $request->content_id : $data['content_id'] = 0;
        ($request->category_new_id) ? $data['category_new_id'] = $request->category_new_id : $data['category_new_id'] = 0;
        ($request->new_id) ? $data['new_id'] = $request->new_id : $data['new_id'] = 0;

        if($seminar_id) $data['seminar_id'] = $seminar_id->id;
        if($request->push == 2 || $request->push == 0) {
            $data['push_date'] = $request->day.' '.$request->time.':'.$request->minute.':00';
        }
        $data['user_lists'] = json_encode($request->users);
        $rs = Message::insertGetId($data);
        // dd($rs);
        if($request->push == 1) {
            $mes = Message::where('id',$rs)->update(['push_date'=> date('Y-m-d H:i:s'), 'push'=> 1]);
            $mes = Message::find($rs);
            $title = $mes->title;
            $body = $mes->message;
            $push_data = [
                'title' => $title,
                'body' => $body,
                'global' => 1,
                'type' => $mes->type,
                'association_id' => $mes->category_id,
            ];
            if($mes->type == 2){
                if($mes->channel_id > 0) $push_data['channel_id'] = $mes->channel_id;
                if($mes->channel_id > 0) $push_data['channel_title'] = @Channel::find($mes->channel_id)->title;
                if($mes->content_id > 0) $push_data['video_id'] = $mes->content_id;
            }
            if($mes->type == 3){
                if($mes->category_new_id > 0) $push_data['category_new_id'] = $mes->category_new_id;
                if($mes->category_new_id > 0) $push_data['category_new_title'] = @CategoryNew::find($mes->category_new_id)->category_name;
                if($mes->new_id > 0) $push_data['news_id'] = $mes->new_id;
            }
            if($mes->type === 5){
                $push_data['seminar_id'] = Seminar::where('category_id', $mes->category_id)->where('publish', 1)->first()->id;
                $push_data['type'] = 1;
            }
            if($request->users){
                $users_push_ar = $request->users;
            
	            $devices = Device::where('token','!=',"")->whereIn('user_id', $users_push_ar)->get();
	            // dd($devices);
	            foreach ($devices as $key => $value) {
	                notification_push($value->token, $title, $push_data, $body);
	            }
	        }
	        // else{
         //        if($request->category_id > 0) {
         //            $users = User::with(['devices'])
         //                ->whereHas('association', function($qr)use($request){
         //                    $qr->where('category_id', $request->category_id);
         //                })
         //                ->has('devices')
         //                ->get();
         //                if(count($users)) {
         //                foreach ($users as $key => $value) {
         //                    if($value->seminar) {
         //                    $seminar_p = json_decode($value->seminar);
         //                    }else{
         //                        $seminar_p = [];
         //                    }
         //                    if (array_search($rs, $seminar_p) == false && array_search($rs, $seminar_p) !== 0) {
         //                        $seminar_p[] =$rs;
         //                    }
                            
         //                    $dataseminar = ['seminar'=>json_encode(array_merge($seminar_p))];
         //                    User::where('id', $value->id)->update($dataseminar);

         //                    foreach ($value->devices as $keyd => $valued) {
         //                        notification_push($valued->token, $title, $push_data, $body);
         //                    }
         //                }
         //            }
         //        }else{
         //            $devices = Device::where('token','!=',"")->get();
         //            // dd($devices);
         //            foreach ($devices as $key => $value) {
         //                notification_push($value->token, $title, $push_data, $body);
         //            }
         //        }
         //    }
        }
        return redirect(route('push.notification.index'));
    }
    public function pushPreview(Request $request)
    {
        $user_id = session('user')['id'];
        $category_id = PushUser::find($user_id)->category_id;
        $title = $request->title;
        $body = $request->message;
        $push_data = [
            'title' => $title,
            'body' => $body,
            'global' => 1,
            'type' => $request->type,
            'association_id' => $category_id,
        ];
        if($request->type == 2){
            $push_data['channel_id'] = $request->channel_id;
            if($request->channel_id) $push_data['channel_title'] = @Channel::find($request->channel_id)->title;
            $push_data['video_id'] = $request->content_id;
        }
        if($request->type == 3){
            $push_data['category_new_id'] = $request->category_new_id;
            if($request->category_new_id) $push_data['category_new_title'] = @CategoryNew::find($request->category_new_id)->category_name;
            $push_data['news_id'] = $request->new_id;
        }
        if($request->type === 5){
            $push_data['seminar_id'] = Seminar::where('category_id', $category_id)->where('publish', 1)->first()->id;
            $push_data['type'] = 1;
        }
        $users_push_ar = User::where('push_preview', 1)->pluck('id')->toArray();
        $devices = Device::where('token','!=',"")->whereIn('user_id', $users_push_ar)->get();
        // dd($devices);
        foreach ($devices as $key => $value) {
            notification_push($value->token, $title, $push_data, $body);
        }
        echo json_encode('1');die();
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function push($id, Request $request)
    {

        $message = Message::find($id);
        $message->push = 1;
        $message->push_date = date('Y-m-d H:i:s');
        $message->save();
        $title = $message->title;
        $body = $message->message;
        $push_data = [
            'title' => $title,
            'body' => $body,
            'global' => 1,
            'type' => $message->type,
            'association_id' => $message->category_id,
        ];
        if($message->type == 2){
            $push_data['channel_id'] = $message->channel_id;
            if($message->channel_id) $push_data['channel_title'] = @Channel::find($message->channel_id)->title;
            $push_data['video_id'] = $message->content_id;
        }
        if($message->type == 3){
            $push_data['category_new_id'] = $message->category_new_id;
            if($message->category_new_id) $push_data['category_new_title'] = @CategoryNew::find($message->category_new_id)->category_name;
            $push_data['news_id'] = $message->new_id;
        }
        if($message->type == 5){
            $push_data['seminar_id'] = Seminar::where('category_id', $message->category_id)->where('publish', 1)->first()->id;
            $push_data['type'] = 1;
        }
        if($message->user_lists){
        	$users_push_ar = json_decode($message->user_lists);
	        $devices = Device::where('token','!=',"")->whereIn('user_id', $users_push_ar)->get();
	        // dd($devices);
	        foreach ($devices as $key => $value) {
	            notification_push($value->token, $title, $push_data, $body);
	        }
        }
        
        
        return redirect(route('push.notification.index'));
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
    public function edit($id, Request $request)
    {
        //
        $user_id = session('user')['id'];
        $category_id = PushUser::find($user_id)->category_id;
        $message = Message::find($id);
        $cate = Category::pluck('category','id')->toArray();
        array_unshift($cate, '全ての学会');
        $careers_list_id = User::whereHas('association', function($qr)use($category_id){
                $qr->where('category_id', $category_id);
            })->groupBy('career_id')->pluck('career_id')->toArray();
        $careers =  Career::whereIn('id', $careers_list_id)->pluck('name','id')->toArray();
        // dd($careers);
        $provinces_list_id = User::whereHas('association', function($qr)use($category_id){
                $qr->where('category_id', $category_id);
            })->groupBy('area_hospital')->pluck('area_hospital')->toArray();
        $provinces =  Province::whereIn('province_name', $provinces_list_id)->pluck('province_name', 'province_name')->toArray();

        $member_id = UserCategory::where("status", 1)->where("category_id", $category_id)->groupBy('member_id')->pluck('member_id')->toArray();
        $members = Member::whereIn('id', $member_id )->pluck('code','id')->toArray();

        $users_push_ar = json_decode($message->user_lists);
        $users = User::whereIn('id', $users_push_ar)->get();
        $ListMemberPush = ListMemberPush::where('category_id', $category_id)->pluck('name','id')->toArray();
        if($message->type == 2){
            if($message->global == 1){
                $channel_id = Channel::where('id','>',0)->first();
                $channel = Channel::whereHas('association', function($qr)use($category_id){
                    $qr->where('category_id', $category_id);
                })->where('id','>',0)->pluck('title','id')->toArray();


                $videos = video::where('channel_id', $message->channel_id)->pluck('title', 'id')->toArray();
                return view('Push::notification.tvpro_edit', compact(['cate', 'channel','videos','careers', 'provinces', 'users','members', 'message','ListMemberPush']));
            }
        }
        if($message->type == 3){
            if($message->global == 1){
                $cate_new_id = CategoryNew::where('id','>',0)->first();
                $cate_new = CategoryNew::where('publish', 1)->pluck('category_name','id')->toArray();
                $news = Newc::where('category_new_id', $cate_new_id->id)->pluck('title', 'id')->toArray();
                 return view('Push::notification.new_edit', compact(['cate','cate_new','news','careers', 'provinces', 'users','members','message','ListMemberPush']));
            }
        }


        return view('Push::notification.edit', compact(['cate', 'careers', 'provinces', 'users','members','message','ListMemberPush']));
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
        //
        // $data = $request->all();
        // unset($data['_token']);
        // unset($data['_method']);
        // $rs = Message::find($id)->update($data);
        // if($request->push == 1) {
        //     Message::find($rs)->update(['push_date', date('Y-m-d H:i:s')]);
        // }
        // return redirect(route('push.notification.index'));
        $validator = Validator::make(
            $request->all(), 
            [
                'title' => 'required',
                'message' => 'required',            ],
            [
                'title.required' => 'タイトルが間違っています。',
                'message.required' => 'messageが間違っています。',
            ]
        );
        $user_id = session('user')['id'];
        $category_id = PushUser::find($user_id)->category_id;
        $seminar_id = 0;
        if($request->type == 5) {
            $seminar = Seminar::where('category_id', $category_id)->first();
            if(!$seminar){
                $validator->after(function ($validator) {
                    $validator->errors()->add('seminar.required', 'Please select a seminar with seminars');
                });
            }
            $seminar_id = Seminar::where('category_id', $category_id)->where('publish', 1)->first();

        }
        if ($validator->fails()) {
            
            return back()
                        ->withErrors($validator)
                        ->withInput();
        }
        // $data = $request->all();
        $data['title'] = $request->title;
        $data['message'] = $request->message;
        $data['type'] = $request->type;
        $data['push'] = $request->push;
        $data['category_id'] = $category_id;
        // dd($data);
        // $data['category_id'] = $request->category_id;
        ($request->channel_id) ? $data['channel_id'] = $request->channel_id : $data['channel_id'] = 0;
        ($request->content_id) ? $data['content_id'] = $request->content_id : $data['content_id'] = 0;
        ($request->category_new_id) ? $data['category_new_id'] = $request->category_new_id : $data['category_new_id'] = 0;
        ($request->new_id) ? $data['new_id'] = $request->new_id : $data['new_id'] = 0;

        if($seminar_id) $data['seminar_id'] = $seminar_id->id;
        if($request->push == 2 || $request->push == 0) {
            $data['push_date'] = $request->day.' '.$request->time.':'.$request->minute.':00';
        }
        
        $data['user_lists'] = json_encode($request->users);
        $rs = Message::find($id)->update($data);
        // dd($rs);
        if($request->push == 1) {
            $mes = Message::where('id',$id)->update(['push_date'=> date('Y-m-d H:i:s'), 'push'=> 1]);
            $mes = Message::find($id);
            $title = $mes->title;
            $body = $mes->message;
            $push_data = [
                'title' => $title,
                'body' => $body,
                'global' => 1,
                'type' => $mes->type,
                'association_id' => $mes->category_id,
            ];
            if($mes->type == 2){
                if($mes->channel_id) $push_data['channel_id'] = $mes->channel_id;
                if($mes->channel_id) $push_data['channel_title'] = @Channel::find($mes->channel_id)->title;
                if($mes->content_id) $push_data['video_id'] = $mes->content_id;
            }
            if($mes->type == 3){
                if($mes->category_new_id) $push_data['category_new_id'] = $mes->category_new_id;
                if($mes->category_new_id) $push_data['category_new_title'] = @CategoryNew::find($mes->category_new_id)->category_name;
                if($mes->new_id) $push_data['news_id'] = $mes->new_id;
            }
            if($mes->type === 5){
                $push_data['seminar_id'] = Seminar::where('category_id', $mes->category_id)->where('publish', 1)->first()->id;
                $push_data['type'] = 1;
            }
            if($request->users){
            $users_push_ar = $request->users;
            
                $devices = Device::where('token','!=',"")->whereIn('user_id', $users_push_ar)->get();
                // dd($devices);
                foreach ($devices as $key => $value) {
                    notification_push($value->token, $title, $push_data, $body);
                }
            }
        }
        return redirect(route('push.notification.index'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id, Request $request)
    {
        //
        Message::destroy($id);
        return redirect(route('push.notification.index'));
    }



    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function get_push_associations(Request $request)
    {
        //
        $user_id = session('user')['id'];
        $category_id = PushUser::find($user_id)->category_id;
        $text_tt = Category::find($category_id);
        if(!$text_tt) return back();
        return view('Push::notification.push_associations', compact(['text_tt', 'id']));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function post_push_associations(Request $request, $id)
    {
        //
        $data = $request->all();
        $data['category_id'] = $id;
        unset($data['_token']);
        // dd($id);
        $rs = Message::insertGetId($data);
        // dd($rs);
        if($request->push == 1) {
            $mes = Message::where('id',$rs)->update(['push_date'=> date('Y-m-d H:i:s')]);;
            $mes = Message::find($rs);
            $title = $mes->title;
            $body = $mes->message;
            $push_data = [
                'title' => $title,
                'body' => $body,
                'global' => 1,
                // 'type' => $mes->type,
                // 'association_id' => $id,
            ];
            // dd($push_data);
            $users = User::with(['devices'])
                ->whereHas('association', function($qr)use($request, $id){
                    $qr->where('category_id', $id);
                })
                ->has('devices')
                ->get();
                if(count($users)) {
                foreach ($users as $key => $value) {
                    foreach ($value->devices as $keyd => $valued) {
                        notification_push($valued->token, $title, $push_data, $body);
                    }
                }
            }
        }
        return redirect(route('push.associations.index'));
    }

    public function push_comment_discussioin(){
        $discussion_comments = CommentDiscussion::where('push', 1)->get();
        foreach ($discussion_comments as $key => $discussion_comment) {
            CommentDiscussion::find($discussion_comment->id)->update(['push'=>0]);
            $discussion_p = Discussion::find($discussion_comment->discussion_id);
            $title = 'ディスカッションに新たなコメントが追加されました。';
            $body = "";
            $push_data = [
                'title' => $title,
                'body' => $body,
                'type' => 4,
                'association_id' => $discussion_p->category_id,
                'category_id' => $discussion_p->category_discussion_id,
                'discussion_id' => $discussion_p->id,
            ];

            $users = User::with(['devices'])
            ->whereHas('mypage_disscusion', function($qr)use($discussion_p){
                $qr->where('discussion_id', $discussion_p->id);
            })
            ->has('devices')
            ->get();
            if(count($users)) {
                foreach ($users as $key => $value) {
                    if($value->discussion) {
                        $discussion_ps = json_decode($value->discussion);
                    }else{
                        $discussion_ps = [];
                    }
                    if (array_search($discussion_p->id, $discussion_ps) == false && array_search($discussion_p->id, $discussion_ps) !== 0) {
                        $discussion_ps[] = $discussion_p->id;
                    }
                    
                    $datadiscussion = ['discussion'=>json_encode(array_merge($discussion_ps))];
                    User::where('id', $value->id)->update($datadiscussion);

                    foreach ($value->devices as $keyd => $valued) {
                        notification_push($valued->token, $title, $push_data,$body);
                    }
                }
            }
        }
    }
    public function api_access_push(Request $request){
        if(!$request->type || !$request->id) {
            $data["statusCode"] = 1;
            $data["message"] = "";
            return response()->json($data);
        }
        $user_id = get_user_id($request);
        if($request->type == 1) {
            AccessVideo::insert(['video_id' => $request->id, "user_id" => $user_id]);
        }
        if($request->type == 2) {
            AccessNew::insert(['new_id' => $request->id, "user_id" => $user_id]);
        }
        $data["statusCode"] = 0;
        $data["message"] = "";
        return response()->json($data);
    }

    public function ajax_get_user(Request $request){
        $user_id = session('user')['id'];
        $category_id = PushUser::find($user_id)->category_id;
        $users = User::select('nickname', 'email', 'birthday', 'id');

        if($category_id) {
            $users = $users->whereHas('association', function($qr)use($category_id){
                $qr->where('category_id', $category_id);
            }); 
        }
        if($request->filter == 2){
        
            if(!$request->reset_filter){
                // echo json_encode($request->member);
                if($request->career) {
                    $users = $users->whereIn('career_id', $request->career);
                }
                if($request->olds) {
                    $datecheck = date('Y') - $request->olds;
                    $datecheck = $datecheck.date('-m-d');
                    $users = $users->where('birthday', '<=',$datecheck);
                }
                if($request->olde) {
                    $datecheck = date('Y') - $request->olde;
                    $datecheck = $datecheck.date('-m-d');
                    $users = $users->where('birthday', '>=',$datecheck);
                }
                if($request->area) {
                    $users = $users->whereIn('area_hospital', $request->area);
                }

            }
        }elseif($request->filter == 1){
            if($request->member) {
                $users = $users->whereHas('association', function($qr)use($request){
                    $qr->whereIn('member_id', $request->member);
                }); 
            }
        }else{
            if($request->listpush) {
                $members = MemberListMemberPush::where('list_member_id', $request->listpush)->pluck('member_id')->toArray();
                $users = $users->whereHas('association', function($qr)use($members){
                    $qr->whereIn('member_id', $members);
                }); 
            }
        }
        $users = $users->get();
        echo json_encode($users);
    }

    public function ajax_get_member_list(Request $request){
        $user_id = session('user')['id'];
        $category_id = PushUser::find($user_id)->category_id;
        $member_id = UserCategory::where("status", 1);
        if($category_id) {
            $member_id = $member_id->where("category_id", $category_id);
        }
        $member_id = $member_id->groupBy('member_id')->pluck('member_id')->toArray();
        $members = Member::select('code','id')->whereIn('id', $member_id )->get();
        echo json_encode($members);
    }
    public function ajax_get_careers_list(Request $request){
        $user_id = session('user')['id'];
        $category_id = PushUser::find($user_id)->category_id;
        $careers_list_id = User::where('id', '>', 0);
        if($category_id) {
            $careers_list_id = $careers_list_id->whereHas("association", function($qr)use($category_id){
                $qr->where('category_id',$category_id);
            });
        }
        $careers_list_id = $careers_list_id->groupBy('career_id')->pluck('career_id')->toArray();
        $careers =  Career::whereIn('id', $careers_list_id)->get();
        echo json_encode($careers);
    }
    public function ajax_get_provinces_list(Request $request){
        $provinces_list_id = User::where('id', '>', 0);
        if($request->category_id) {
            $provinces_list_id = $provinces_list_id->whereHas("association", function($qr)use($request){
                $qr->where('category_id',$request->category_id);
            });
        }
        $provinces_list_id = $provinces_list_id->groupBy('area_hospital')->pluck('area_hospital')->toArray();
        $provinces =  Province::whereIn('province_name', $provinces_list_id)->get();
        echo json_encode($provinces);
    }
    public function list_user_review(Request $request){
        $users = User::where('roles',2)->pluck('email', 'id')->toArray();
        $userreviews = User::where('push_preview', 1)->paginate(20);
        if ($request->isMethod('post')) {
            if($request->id) {
                User::find($request->id)->update(['push_preview'=>1]);
                return redirect(route('push.user.list.review'));
            }

        }
        if ($request->isMethod('get')) {
            if($request->id) {
                User::find($request->id)->update(['push_preview'=>0]);
                return redirect(route('push.user.list.review'));
            }

        }
        return view('Push::notification.review', compact(['users', 'userreviews']));
    }

    public function cron_push_global()
    {
        $messages = Message::where('push_date', date('Y-m-d H:i:01'))->get();
        if(count($messages)) {
            foreach ($messages as $key => $value) {
                $message = Message::find($value->id);
                $message->push = 1;
                $message->push_date = date('Y-m-d H:i:s');
                $message->save();
                $title = $message->title;
                $body = $message->message;
                $push_date = [
                    'title' => $title,
                    'body' => $body,
                    'global' => 1,
                    'type' => $message->type,
                    'association_id' => $message->category_id,
                ];
                if($message->type == 2){
                    $push_data['channel_id'] = $message->channel_id;
                    if($message->channel_id) $push_data['channel_title'] = @Channel::find($message->channel_id)->title;
                    $push_data['video_id'] = $message->content_id;
                }
                if($message->type == 3){
                    $push_data['category_new_id'] = $message->category_new_id;
                    if($message->category_new_id) $push_data['category_new_title'] = @CategoryNew::find($message->category_new_id)->category_name;
                    $push_data['news_id'] = $message->new_id;
                }
                if($message->type == 5){
                    $push_data['seminar_id'] = Seminar::where('category_id', $message->category_id)->where('publish', 1)->first()->id;
                    $push_data['type'] = 1;
                }

                $users_push_ar = json_decode($message->user_lists);
                $devices = Device::where('token','!=',"")->whereIn('user_id', $users_push_ar)->get();
                // dd($devices);
                foreach ($devices as $key => $value) {
                    notification_push($value->token, $title, $push_data, $body);
                }
            }
        }
        die();
    }
    
}
