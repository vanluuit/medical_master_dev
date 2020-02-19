<?php

namespace App\Modules\Push\Controllers;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Channel;
use App\Category;
use App\CategoryChannel;
use App\ChannelCategory;
use App\Video;
use App\Slider;
use App\User;
use App\LikeVideo;
use App\ViewChannel;
use App\PushUser;

use SocketIO;
use Illuminate\Support\Facades\Storage;

use DB;

class ChannelController extends Controller
{
    // api
    
    public function api_list_by_association(Request $request)
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
        $content_p = [];
        if($push_user->content) {
            $content_p = json_decode($push_user->content);
        }
        
        $category_id = $request->association_id;
        // return response()->json($category_id);
        // $channels = Channel::with(['association'])->get();
        $callback = function($query)use($category_id) {
            $query->where('category_id', $category_id);
        };

        if($request->channel_id) {
            $contents = Video::with(['stop_time'=>function($qr)use($user_id){
                $qr->where('user_id', $user_id);
            }])->select(['id','channel_id','title','discription','thumbnail','thumbnail_detail','video','type'])->where('channel_id', $request->channel_id)
                ->where('publish',0)
                ->whereDate('start_date','<', date('Y-m-d H:i:s'))
                ->orderBy('start_date' , 'DESC')->paginate(4);
            foreach ($contents as $keyvd => $valuevd) {
                list($width, $height) = @getimagesize('/var/www/html'.$valuevd->thumbnail);
                $valuevd->width = @$width;
                $valuevd->height = @$height;
                if($valuevd->type==1) {
                    $file = '/var/www/html'.$valuevd->video;
                    $time_total = exec('/usr/local/bin/bin/ffmpeg -i '.$file.' 2>&1 | grep "Duration" | cut -d " " -f 4 | sed s/,//');
                    $valuevd->time_total = covert_total_time($time_total);
                }
                if(@$valuevd->stop_time){
                    $stop_time = @$valuevd->stop_time[0];
                    unset($valuevd->stop_time);
                    $valuevd->stop_time =  $stop_time;
                }
            }
            $data["statusCode"] = 0;
            $data["message"] = "";
            $data['data'] = $contents;
            return response()->json($data);
        }

        $channels = Channel::select('id','logo','title','discription','is_sponser','sponser','publish_date', 'created_at', DB::raw('RAND() as rand_od'))
            ->withCount(['count_like', 'is_like'=>function($qr1)use($user_id){
                    $qr1->where('user_id', $user_id);
                },'count_mypage','is_mypage'=>function($qr1)use($user_id){
                    $qr1->where('user_id', $user_id);
                }, 'contents'=>function($qr1)use($content_p){
                    $qr1->whereIn('id', $content_p);
                }])
            ->where('publish_date','<',date('Y-m-d H:i:s'))
            ->where('publish',0)
            ->whereHas('association', function($query)use($category_id) {
                $query->where('category_id', $category_id);
            });
            if($push_user->career_id == 20){
                $channels = $channels->where('is_sponser', 0);
            }
            $channels = $channels->orderBy('nabi' , 'DESC')->orderBy('rand_od', 'DESC')
            ->get();

        if(count($channels)) {
            foreach ($channels as $keyr => $valuer) {
                if(in_array($valuer->id, $channel_p)) {
                   $valuer->unread = 1;
                }else{
                    $valuer->unread = 0;
                }
                if($valuer->contents_count > 0){
                    $valuer->unread = 1;
                }
                $contents = Video::with(['stop_time'=>function($qr)use($user_id){
                    $qr->where('user_id', $user_id);
                }])->select(['id','channel_id','title','discription','thumbnail','thumbnail_detail','video','type'])->where('channel_id', $valuer->id)
                ->where('publish',0)
                ->whereDate('start_date','<', date('Y-m-d H:i:s'))
                ->orderBy('start_date' , 'DESC')->paginate(4);
                foreach ($contents as $keyvd => $valuevd) {
                    list($width, $height) = @getimagesize('/var/www/html'.$valuevd->thumbnail);
                    $valuevd->width = @$width;
                    $valuevd->height = @$height;
                    if($valuevd->type==1) {
                        $file = '/var/www/html'.$valuevd->video;
                        $time_total = exec('/usr/local/bin/bin/ffmpeg -i '.$file.' 2>&1 | grep "Duration" | cut -d " " -f 4 | sed s/,//');
                        $valuevd->time_total = covert_total_time($time_total);
                    }
                    if(@$valuevd->stop_time){
                        $stop_time = @$valuevd->stop_time[0];
                        unset($valuevd->stop_time);
                        $valuevd->stop_time =  $stop_time;
                    }
                }
                $valuer->contents = $contents;
            }
            $data["statusCode"] = 0;
            $data["message"] = "";
            $data['data'] = $channels;  
        }else{
            $data["statusCode"] = 0;
            $data["message"] = "";
            $data['data'] = $channels;
        }
        
        
        return response()->json($data);
    }


public function api_list_by_top(Request $request)
    {
        $user_id = get_user_id($request);
        $push_user = User::where('id', $user_id)->first();
        $category_id = $request->association_id;
        // $channels = Channel::with(['association'])->get();
        $callback = function($query)use($category_id) {
            $query->where('category_id', $category_id);
        };
        $channels = Channel::select('id','logo','title','discription','is_sponser','sponser','publish_date','created_at', DB::raw('RAND() as rand_od'))
            ->withCount(['count_like', 'is_like'=>function($qr1)use($user_id){
                    $qr1->where('user_id', $user_id);
                },'count_mypage','is_mypage'=>function($qr1)use($user_id){
                    $qr1->where('user_id', $user_id);
                }])
            ->whereHas('association', function($query)use($category_id) {
                $query->where('category_id', $category_id);
            })
            ->where('publish',0)
            ->whereDate('publish_date','<',date('Y-m-d H:i:s'));
            if($push_user->career_id == 20){
                $channels = $channels->where('is_sponser', 0);
            }
            $channels = $channels->orderBy('nabi' , 'DESC')->orderBy('rand_od', 'DESC')
            ->get();
        if(count($channels)) {
            $data["statusCode"] = 0;
            $data["message"] = "";
            $data['data'] = $channels;  
        }else{
            $data["statusCode"] = 0;
            $data["message"] = "";
            $data['data'] = $channels;
        }
        
        
        return response()->json($data);
    }


    public function api_list_month_expect(Request $request)
    {
        $user_id = get_user_id($request);

        // $category_id = $request->association_id;
        $callback = function($query)use($user_id) {
            $query->where('user_id', $user_id);
        };
        $categories = Category::whereHas('user_category', $callback)->with(['user_category' => $callback])->where('publish', 0)->pluck('id');
        $channels_ar = Channel::whereHas('association', function($query)use($categories) {
                $query->whereIn('category_id', $categories);
            })
            ->where('publish',0)
            ->pluck('id');
            // ->groupBy('month_expect')
            // ->orderBy('publish_date','DESC')
            // ->get();
            // return response()->json($channels_ar);

        $channels = Video::select(DB::raw('DATE_FORMAT(start_date, "%Y-%m") as month_expect'))
            ->where('start_date','>', date('Y-m-d H:i:s'))
            ->whereIn('channel_id', $channels_ar)
            // ->whereHas('association', function($query)use($categories) {
            //     $query->whereIn('category_id', $categories);
            // })
            ->where('publish',0)
            ->groupBy('month_expect')
            ->orderBy('month_expect' , 'DESC')
            ->get();
        // $channels = [];
        if(count($channels)) {
            $data["statusCode"] = 0;
            $data["message"] = "";
            $data['data'] = $channels;  
        }else{
            $data["statusCode"] = 0;
            $data["message"] = "";
            $data['data'] = $channels;
        }
        
        
        return response()->json($data);
    }


    public function api_list_expect_publish(Request $request)
    {
        $user_id = get_user_id($request);

        // $category_id = $request->association_id;
        $callback = function($query)use($user_id) {
            $query->where('user_id', $user_id);
        };
        $s_date = $request->month.'-01';
        $e_date = $request->month.'-'.date('t',strtotime($s_date)).' 23:59:59';
        // return response()->json($e_date);
        $categories = Category::whereHas('user_category', $callback)->with(['user_category' => $callback])->where('publish', 0)->pluck('id');
        // return response()->json($categories);
        $channels_ar = Channel::whereHas('association', function($query)use($categories) {
                $query->whereIn('category_id', $categories);
            })
            ->where('publish',0)
            ->pluck('id');

        $videos = Video::select('id','title','discription','thumbnail','video','sponser', 'related_videos_1','related_videos_2','related_videos_3','type', 'view', 'ads_text', 'ads_url', 'ads_text_2', 'ads_url_2', 'start_date', 'created_at')
        ->withCount(['count_like', 'is_like'=>function($qr1)use($user_id){
                    $qr1->where('user_id', $user_id);
                }])
        ->whereIn('channel_id',$channels_ar)
        ->where('start_date','>', date('Y-m-d H:i:s'))
        ->where('publish',0)
        ->where('start_date','>=', $s_date)
        ->where('start_date','<=', $e_date)
        ->orderBy('nabi','ASC')
        ->take(10)
        ->get();
        
        // $channels = Channel::select('id','logo','title','discription','sponser','publish_date')
        //     ->where('publish_date','>', date('Y-m-d H:i:s'))
        //     ->where('publish_date','>=', $s_date)
        //     ->where('publish_date','<=', $e_date)
        //     ->withCount(['count_like', 'is_like'=>function($qr1)use($user_id){
        //             $qr1->where('user_id', $user_id);
        //         },'count_mypage','is_mypage'=>function($qr1)use($user_id){
        //             $qr1->where('user_id', $user_id);
        //         }])
        //     ->whereHas('association', function($query)use($categories) {
        //         $query->whereIn('category_id', $categories);
        //     })
            
        //     ->orderBy('id','DESC')
        //     ->get();
        // $videos = [];
        if(count($videos)) {
            $data["statusCode"] = 0;
            $data["message"] = "";
            $data['data'] = $videos;  
        }else{
            $data["statusCode"] = 0;
            $data["message"] = "";
            $data['data'] = $videos;
        }
        
        
        return response()->json($data);
    }
    public function api_like_set(Request $request)
    {
        $user_id = get_user_id($request);
        $check = DB::table('like_channels')->where('channel_id', $request->id)->where('user_id', $user_id)->first();
        if($check) {
            $rs = DB::table('like_channels')->where('channel_id', $request->id)->where('user_id', $user_id)->delete();
        }else{
            $rs = DB::table('like_channels')->insert([
                'channel_id' => $request->id,
                'user_id' => $user_id,
            ]);
        }
        if($rs) {
            $data["statusCode"] = 0;
            $data["message"] = ""; 
        }else{
            $data["statusCode"] = 1;
            $data["message"] = "error";
        }
        return response()->json($data);
    }

    public function api_mypage_set(Request $request)
    {
        $user_id = get_user_id($request);
        $check = DB::table('user_channels')->where('channel_id', $request->id)->where('user_id', $user_id)->first();
        if($check) {
            $rs = DB::table('user_channels')->where('channel_id', $request->id)->where('user_id', $user_id)->delete();
            $data["data"]["is_mypage_count"] = 0;
        }else{
            $rs = DB::table('user_channels')->insert([
                'channel_id' => $request->id,
                'user_id' => $user_id,
            ]);
            $data["data"]["is_mypage_count"] = 1;
        }
        if($rs) {
            $data["statusCode"] = 0;
            $data["message"] = ""; 
        }else{
            $data["statusCode"] = 1;
            $data["message"] = "error";
        }
        return response()->json($data);
    }

    public function api_mypage_list(Request $request)
    {
        $user_id = get_user_id($request);

        $category_id = $request->association_id;
        // $channels = Channel::with(['association'])->get();
        $callback = function($query)use($category_id) {
            $query->where('category_id', $category_id);
        };
        $categories = Category::whereHas('user_category', $callback)->with(['user_category' => $callback])->where('publish', 0)->pluck('id');
        // return response()->json($category_id);
        $channels = Channel::select('id','logo','title','discription','sponser','publish_date')
            ->withCount(['count_like', 'is_like'=>function($qr1)use($user_id){
                    $qr1->where('user_id', $user_id);
                },'count_mypage','is_mypage'=>function($qr1)use($user_id){
                    $qr1->where('user_id', $user_id);
                }]);
            if($request->association_id) {
                $channels = $channels->whereHas('association', function($query)use($category_id,$categories ) {
                    $query->where('category_id', $category_id)->whereIn('category_id', $categories);
                });
            }
            
            // ->whereDate('publish_date','<',date('Y-m-d H:i:s'));
            if($request->expect && $request->expect==1){
                $channels = $channels->whereDate('publish_date','>',date('Y-m-d H:i:s'));
            }else{
                $channels = $channels->whereDate('publish_date','<',date('Y-m-d H:i:s'));
            }
            $channels = $channels->whereHas('is_mypage', function($qrh)use($user_id){
                $qrh->where('user_id', $user_id);
            })
            ->where('publish',0)
            ->orderBy('publish_date' , 'DESC')
            ->take(10)
            ->get();

        if(count($channels)) {
            $data["statusCode"] = 0;
            $data["message"] = "";
            $data['data'] = $channels;  
        }else{
            $data["statusCode"] = 0;
            $data["message"] = "";
            $data['data'] = $channels;
        }
        
        
        return response()->json($data);
    }

    public function api_list_category(Request $request)
    {
        $categorys = CategoryChannel::select('id', 'name')->get();
        if(count($categorys)) {
            $data["statusCode"] = 0;
            $data["message"] = ""; 
            $data["data"] = $categorys; 
        }else{
            $data["statusCode"] = 1;
            $data["message"] = "error";
            $data["data"] = $categorys; 
        }
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
        // dd(array_search(3, [0=>1, 1=>2]));
        $channels = Channel::with(['association','association.category']);
        if($request->category_id) {
            $channels = $channels->whereHas('association', function($query)use($request) {
                $query->where('category_id', $request->category_id);
            });
        }
        
        // ->whereDate('publish_date','<',date('Y-m-d H:i:s'))
        $channels= $channels->where('id','>',0);
        if($request->except && $request->except==1){
            $channels = $channels->whereDate('publish_date','>',date('Y-m-d H:i:s'));
        }
        $channels = $channels->orderBy('nabi', 'DESC')->get();
        $categories = Category::pluck('category','id');
        return view('Push::channels.index',compact(['channels','categories']));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        $categories = Category::pluck('category','id');
        // $cates = CategoryChannel::pluck('name','id');
        return view('Push::channels.create', compact('categories'));
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
        $data = $request->all();
        // dd($request->category_id);
        // $channel_check = Channel::where('category_id', $request->category_id)->first();
        
        unset($data['_token']);
        unset($data['category_id']);
        // dd($data);
        // if(!$channel_check) {
        //     $data['nabi'] = 1;
        // }
        // dd($request->category_id);
        $rs = Channel::insertGetId($data);
        if($rs) {
            if($request->logo){
                $path = '/storage/app/'.@$request->file('logo')->store('channel');
                $new = Channel::find($rs);
                $new->logo = $path;
                $new->save();
            }
            $chan = Channel::find($rs);
            foreach ($request->category_id as $key => $value) {
                ChannelCategory::insert([
                    'channel_id' => $rs,
                    'category_id' => $value,
                ]);
                // $title = 'TV Proに新しいチャンネルが追加されました';
                // $body = $chan->title;
                // $push_data = [
                //     'title' => $title,
                //     'body' => $body,
                //     'type' => 2,
                //     'association_id' => $value,
                //     'channel_id' => $rs
                // ];
                // $users = User::with(['devices'])
                // ->whereHas('association', function($qr)use($value){
                //     $qr->where('category_id', $value);
                // })
                // ->has('devices')
                // ->get();
                // if(count($users)) {
                //     foreach ($users as $key => $value) {
                //         foreach ($value->devices as $keyd => $valued) {
                //             notification_push($valued->token, $title, $push_data, $body);
                //         }
                //     }
                // }
            }

            return redirect(route('push.channels.index'));
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
        $categories = Category::pluck('category','id');
        // $cates = CategoryChannel::pluck('name','id');
        $channel = Channel::with(['association'])->find($id);
        // dd($channel->association);
        $association = [];
        if($channel->association) {
            foreach ($channel->association as $key => $value) {
                $association[] = $value->category_id;
            }
            unset($channel->association);
            $channel->association = json_encode($association);
        }

        return view('Push::channels.edit', compact('categories','channel'));
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
        $data = $request->all();
        unset($data['_token']);
        unset($data['_method']);
        unset($data['category_id']);
        // dd($request->category_id);
        $rs = Channel::find($id)->update($data);
        
        if($rs) {
            if($request->logo){
                $channel = Channel::find($id);
                $logo_u = $channel->logo;
                $logo_u = str_replace('/storage/app/', '', $logo_u);
                $path = '/storage/app/'.@$request->file('logo')->store('channel');
                $channel->logo = $path;
                $channel->save();
                Storage::delete($logo_u);
            }
            $channel = Channel::find($id);
            // foreach ($channel->association as $key => $value) {
            ChannelCategory::where('channel_id',$channel->id)->delete();
            // }
            foreach ($request->category_id as $key => $value) {
                ChannelCategory::insert([
                    'channel_id' => $channel->id,
                    'category_id' => $value,
                ]);
            }

            return redirect(route('push.channels.index'));
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
    public function destroy($id, Request $request)
    {
        //
        $videos = Video::where('channel_id', $id)->get();
        foreach ($videos as $key => $video) {
            LikeVideo::where('video_id', $video->id)->delete();
            $slid = Slider::where('video_id', $video->id)->get();
            if(count($slid)) {
                foreach ($slid as $key => $value) {
                    $slider_u = $value->image;
                    $slider_u = str_replace('/storage/app/', '', $slider_u);
                    Storage::delete($slider_u);
                }
            }
            
            Slider::where('video_id', $video->id)->delete();
            $video = Video::find($video->id);
            if($video->thumbnail) {
                $thumbnail_u = $video->thumbnail;
                $thumbnail_u = str_replace('/storage/app/', '', $thumbnail_u);
                Storage::delete($thumbnail_u);
            }
            if($video->video) {
                $video_u = $video->video;
                $video_u = str_replace('/storage/app/', '', $video_u);
                Storage::delete($video_u);
            }
            Video::destroy($video->id);
        }
        Channel::destroy($id);
        viewChannel::where('channel_id',$id)->delete();

        return redirect(route('push.channels.index').'?category_id='.$request->category_id);
    }

    public function destroyall(Request $request)
    {
        //
        if(@$request->delete) {
            foreach ($request->delete as $key => $id) {
                $videos = Video::where('channel_id', $id)->get();
                foreach ($videos as $key => $video) {
                    LikeVideo::where('video_id', $video->id)->delete();
                    $slid = Slider::where('video_id', $video->id)->get();
                    if(count($slid)) {
                        foreach ($slid as $key => $value) {
                            $slider_u = $value->image;
                            $slider_u = str_replace('/storage/app/', '', $slider_u);
                            Storage::delete($slider_u);
                        }
                    }
                    
                    Slider::where('video_id', $video->id)->delete();
                    $video = Video::find($video->id);
                    if($video->thumbnail) {
                        $thumbnail_u = $video->thumbnail;
                        $thumbnail_u = str_replace('/storage/app/', '', $thumbnail_u);
                        Storage::delete($thumbnail_u);
                    }
                    if($video->video) {
                        $video_u = $video->video;
                        $video_u = str_replace('/storage/app/', '', $video_u);
                        Storage::delete($video_u);
                    }
                    Video::destroy($video->id);
                }
                Channel::destroy($id);
                viewChannel::where('channel_id',$id)->delete();
            }
        }
        return redirect(route('push.channels.index').'?category_id='.$request->category_id);
    }

     /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function ajaxpublish(Request $request)
    {
        // $user = User::find($id);
        $category = Channel::find($request->id);
        $category->publish = $request->publish;
        $category->save();
        echo $category->publish; die();
    }

    public function ajaxis_sponser(Request $request)
    {
        // $user = User::find($id);
        $category = Channel::find($request->id);
        $category->is_sponser = $request->sponser;
        $category->save();
        echo $category->is_sponser; die();
    }

    public function ajaxtop(Request $request)
    {
        // $user = User::find($id);
        $category = Channel::find($request->id);
        $category_id = ChannelCategory::where('channel_id', $category->id)->first()->category_id;
        $ids = ChannelCategory::where('category_id', $category_id)->pluck('channel_id')->toArray();
        Channel::whereIn('id', $ids)->update(['nabi'=>0]);
        // dd($ids);
        $category->nabi = 1;
        $category->save();
        echo $category->nabi; die();
    }

     /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function ajaxsoft(Request $request)
    {
        //
        $id = $request->id;
        // $category_id = $request->category_id;
        $id = $id."cc";
        $id = str_replace('_cc', '', $id);
        // $soft = $soft."cc";
        // $soft = str_replace('_cc', '', $soft);
        $id_ar = explode('_', $id);
        // $soft_ar = explode('_', $soft);
        echo json_encode($id_ar);
        // echo json_encode($soft_ar);
        $nabi = 1;
        foreach ($id_ar as $key => $value) {
            Channel::where('id', $value)->update([
                'nabi' => $nabi
            ]);
            $nabi=0;
        }
        echo '1';die();
    }

    public function pushNotification($id, Request $request){
        $channels = ChannelCategory::where('channel_id', $id)->get();
        $chan = Channel::find($id);
        foreach ($channels as $key => $value) {

            $title = 'TV Proに新しいチャンネルが追加されました';
            $body = $chan->title;
            $push_data = [
                'title' => $title,
                'body' => $chan->title,
                'type' => 2,
                'association_id' => $value->category_id,
                'channel_id' => $id
            ];
            $users = User::with(['devices'])
            ->whereHas('association', function($qr)use($value){
                $qr->where('category_id', $value->category_id);
            })
            ->has('devices')
            ->get();
            if(count($users)) {
                foreach ($users as $key => $value) {
                    if($value->channel) {
                        $channel_p = json_decode($value->channel);
                    }else{
                        $channel_p = [];
                    }
                    if (array_search($id, $channel_p) == false && array_search($id, $channel_p) !== 0) {
                        $channel_p[] = $id;
                    }
                    
                    $datachannel = ['channel'=>json_encode(array_merge($channel_p))];
                    User::where('id', $value->id)->update($datachannel);
                    foreach ($value->devices as $keyd => $valued) {
                        notification_push($valued->token, $title, $push_data, $body);
                    }
                }
            }
        }
        return redirect(route('push.channels.index').'?category_id='.$request->category_id);
    }
    public function listByCategory(Request $request){
        $user_id = session('user')['id'];
        $category_id = PushUser::find($user_id)->category_id;
        $channel = Channel::select(['id', 'title']);
        if($category_id) {
            $channel = $channel->whereHas('association', function($qr)use($category_id){
                $qr->where('category_id', $category_id);
            });
        }
        $channel = $channel->get();
        return json_encode($channel);
    }

}
