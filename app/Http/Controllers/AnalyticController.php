<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\ViewChannel;
use App\ViewVideo;
use App\ViewNew;
use App\ViewEvent;
use App\Channel;
use App\Video;
use App\Newc;
use App\Event;
use App\Category;
use App\Banner;
use Validator;
use DB;

class AnalyticController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function api_content_add_view(Request $request)
    {
        //
        $validator = Validator::make(
            $request->all(), 
            [
                'id' => 'required',
            ],
            [
                'id.required' => 'video_idが間違っています。',
            ]
        );
        if ($validator->fails()) {
            $data["statusCode"] = 1;
            $data["message"] = "Error occurred when set view";
            $data['validator'] = $validator->errors();
            return response()->json($data);
        }
        $user_id = get_user_id($request);

        $view = new ViewVideo;
        $view->user_id = $user_id;
        $view->video_id = $request->id;
        $view->time_view = @$request->time_view ? @$request->time_view : 0;
        $view->time_total = @$request->time_total ? @$request->time_total : 0;
        if(@$request->time_view > 0){
            if(@$request->time_view ==  @$request->time_total) {
                $view->status = 1;
            }
        }
        $rs = $view->save();
        if($rs) {
            $data["statusCode"] = 0;
            $data["message"] = ""; 
        }else{
            $data["statusCode"] = 1;
            $data["message"] = "error";
        }
        return response()->json($data);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function api_channel_add_view(Request $request)
    {
        //
        $validator = Validator::make(
            $request->all(), 
            [
                'id' => 'required',
            ],
            [
                'id.required' => 'channel_idが間違っています。',
            ]
        );
        if ($validator->fails()) {
            $data["statusCode"] = 1;
            $data["message"] = "Error occurred when set view";
            $data['validator'] = $validator->errors();
            return response()->json($data);
        }
        $user_id = get_user_id($request);

        $view = new ViewChannel;
        $view->user_id = $user_id;
        $view->channel_id = $request->id;
        $rs = $view->save();
        if($rs) {
            $data["statusCode"] = 0;
            $data["message"] = ""; 
        }else{
            $data["statusCode"] = 1;
            $data["message"] = "error";
        }
        return response()->json($data);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function api_new_add_view(Request $request)
    {
        //
        $validator = Validator::make(
            $request->all(), 
            [
                'id' => 'required',
            ],
            [
                'id.required' => 'new_idが間違っています。',
            ]
        );
        if ($validator->fails()) {
            $data["statusCode"] = 1;
            $data["message"] = "Error occurred when set view";
            $data['validator'] = $validator->errors();
            return response()->json($data);
        }
        $user_id = get_user_id($request);

        $view = new ViewNew;
        $view->user_id = $user_id;
        $view->new_id = $request->id;
        $rs = $view->save();
        if($rs) {
            $data["statusCode"] = 0;
            $data["message"] = ""; 
        }else{
            $data["statusCode"] = 1;
            $data["message"] = "error";
        }
        return response()->json($data);
    }
    public function api_event_add_view(Request $request)
    {
        //
        $validator = Validator::make(
            $request->all(), 
            [
                'id' => 'required',
            ],
            [
                'id.required' => 'event_idが間違っています。',
            ]
        );
        if ($validator->fails()) {
            $data["statusCode"] = 1;
            $data["message"] = "Error occurred when set view";
            $data['validator'] = $validator->errors();
            return response()->json($data);
        }
        $user_id = get_user_id($request);

        $view = new ViewEvent;
        $view->user_id = $user_id;
        $view->event_id = $request->id;
        $rs = $view->save();
        if($rs) {
            $data["statusCode"] = 0;
            $data["message"] = ""; 
        }else{
            $data["statusCode"] = 1;
            $data["message"] = "error";
        }
        return response()->json($data);
    }
    public function api_banner_add_view(Request $request)
    {
        //
        $validator = Validator::make(
            $request->all(), 
            [
                'id' => 'required',
            ],
            [
                'id.required' => 'banner_idが間違っています。',
            ]
        );
        if ($validator->fails()) {
            $data["statusCode"] = 1;
            $data["message"] = "Error occurred when set view";
            $data['validator'] = $validator->errors();
            return response()->json($data);
        }
        $user_id = get_user_id($request);

        $view = new ViewBanner;
        $view->user_id = $user_id;
        $view->banner_id = $request->id;
        $rs = $view->save();
        if($rs) {
            $data["statusCode"] = 0;
            $data["message"] = ""; 
        }else{
            $data["statusCode"] = 1;
            $data["message"] = "error";
        }
        return response()->json($data);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function channels(Request $request)
    {
        //
        $this_month = date('Y-m-01');
        $this_month_end = date('Y-m-t').' 23:59:59';
        $last_month = date('Y-m-01');
        $last_month_end = date('Y-m-t').' 23:59:59';
        // dd($this_month_end);
        $channels = Channel::withCount(['count_view', 'count_view_m'=>function($qr)use($this_month, $this_month_end){
            $qr->where('created_at', '>', $this_month)
                ->where('created_at', '<=', $this_month_end);
        }, 'count_view_l'=>function($qr)use($last_month, $last_month_end){
            $qr->where('created_at', '>', $last_month)
                ->where('created_at', '<=', $last_month_end);
        }]);
        if($request->s) {
            $channels = $channels->where('title', 'like', "%".$request->s."%");
        }
        $channels = $channels->paginate(20);

        return view('analytic.channel', compact(['channels']));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    // public function content(Request $request)
    // {
    //     //
    //     $this_month = date('Y-m-01');
    //     $this_month_end = date('Y-m-t').' 23:59:59';
    //     $last_month = date('Y-m-01', strtotime("last month"));
    //     $last_month_end = date('Y-m-t', strtotime("last month")).' 23:59:59';
    //     // dd($this_month_end);
    //     $contents = Video::with(['sum_time'])
    //     ->withCount(['count_view', 'count_view_m'=>function($qr)use($this_month, $this_month_end){
    //         $qr->where('created_at', '>', $this_month)
    //             ->where('created_at', '<=', $this_month_end);
    //     } , 'count_view_l'=>function($qr)use($last_month, $last_month_end){
    //         $qr->where('created_at', '>', $last_month)
    //             ->where('created_at', '<=', $last_month_end);
    //     }, 'complete'=>function($qr){
    //         $qr->where('status', 1);
    //     }, 'withdrawal'=>function($qr){
    //         $qr->where('status', 0);
    //     }]);
    //     if($request->s) {
    //         $contents = $contents->where('title', 'like', "%".$request->s."%");
    //     }
    //     $contents = $contents->paginate(20);
    //     // dd($contents);
    //     return view('analytic.content', compact(['contents']));
    // }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function content(Request $request)
    {
        if(!@$request->start_day) {
            $start_day = date('Y-m-01');
        }else{
            $start_day = $request->start_day;
        }
        if(!@$request->end_day) {
            $end_day = date('Y-m-t');
        }else{
            $end_day = $request->end_day;
        }

        $contents = Video::with(['sum_time'])
        ->withCount(['count_view', 'count_view_m'=>function($qr)use($start_day, $end_day){
            $qr->where('created_at', '>', $start_day)
                ->where('created_at', '<=', $end_day.' 23:59:59');
        }, 'complete'=>function($qr){
            $qr->where('status', 1);
        }, 'withdrawal'=>function($qr){
            $qr->where('status', 0);
        }, 'complete_m'=>function($qr)use($start_day, $end_day){
            $qr->where('status', 1)
                ->where('created_at', '>', $start_day)
                ->where('created_at', '<=', $end_day.' 23:59:59');
        }, 'withdrawal_m'=>function($qr)use($start_day, $end_day){
            $qr->where('status', 0)
                ->where('created_at', '>', $start_day)
                ->where('created_at', '<=', $end_day.' 23:59:59');
        }]);
        if($request->s) {
            $contents = $contents->where('title', 'like', "%".$request->s."%");
        }
        $contents = $contents->paginate(20);
        if ($request->isMethod('post')) {
            $viewlists = ViewVideo::with(['user.career','user.faculty'])
            ->where('video_id', $request->id)
            ->where('created_at', '>=', $start_day)
            ->where('created_at', '<=', $end_day.' 23:59:59')
            ->get();
            $titles = ['Nickname','name','date of birth','prefecture','profession','clinical department','registration date','viewing date','viewing time'];
            foreach ($viewlists as $key => $value) {
                $datas[$key]['Nickname'] = @$value->user->nickname;
                $datas[$key]['name'] = @$value->user->firstname.' '.@$value->user->lastname;
                $datas[$key]['birthday'] = @$value->user->birthday;
                $datas[$key]['area_hospital'] = @$value->user->area_hospital;
                $datas[$key]['career'] = @$value->user->career->name;
                $datas[$key]['faculty'] = @$value->user->faculty->name;
                $datas[$key]['registration'] = @$value->user->created_at;
                $datas[$key]['access'] = @$value->created_at;
                $datas[$key]['time_view'] = time_convert(@$value->time_view);

            }
            $this->exportF($titles, $datas, 'report_access_video.csv');
        }
        // dd($contents);
        return view('analytic.content', compact(['contents','start_day','end_day']));
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function contentDetail(Request $request, $id)
    {
        if(!@$request->start_day) {
            $start_day = date('Y-m-01');
        }else{
            $start_day = $request->start_day;
        }
        if(!@$request->end_day) {
            $end_day = date('Y-m-t');
        }else{
            $end_day = $request->end_day;
        }

        // $this_month = date('Y-m-01', strtotime("last month"));
        // $this_month_end = date('Y-m-t', strtotime("last month")).' 23:59:59';
        // dd($this_month_end);
        $contents = Video::where('id', $id)
            ->withCount(['count_view', 'count_view_m'=>function($qr)use($start_day, $end_day){
                $qr->where('created_at', '>', $start_day)
                    ->where('created_at', '<=', $end_day.' 23:59:59');
            }, 'complete'=>function($qr){
                $qr->where('status', 1);
            }, 'withdrawal'=>function($qr){
                $qr->where('status', 0);
            }]);
        $contents = $contents->first();

        $time_total = ViewVideo::select(DB::raw('SUM(time_view) as total_time'))
            ->where('created_at', '>', $start_day)
            ->where('created_at', '<=', $end_day.' 23:59:59')
            ->where('video_id', $id)
            ->groupBy('video_id')
            ->first();

        $viewlists = ViewVideo::select(DB::raw('COUNT(DISTINCT (case when status = 1 then user_id end)) as comp_u,COUNT(DISTINCT (case when status = 0 then user_id end)) as withx_u, COUNT(IF(status=1, 1 , NULL)) as comp ,COUNT(IF(status=0, 1 , NULL)) as withx ,COUNT(*) as total, SUM(time_view) as total_time ,DATE_FORMAT(created_at, "%Y-%m-%d") as day_list'))
            ->where('created_at', '>', $start_day)
            ->where('created_at', '<=', $end_day.' 23:59:59')
            ->where('video_id', $id)
            ->groupBy('day_list')
            ->get();
        // dd($time_total);
        return view('analytic.contentDetail', compact(['contents','viewlists','id','time_total','start_day', 'end_day']));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function contentDetailDay(Request $request, $id, $day)
    {
        if(!@$request->day) return back();
        $start_day = $request->day;
        $end_day = $request->day;

        $contents = Video::where('id', $id)
            ->withCount(['count_view', 'count_view_m'=>function($qr)use($start_day, $end_day){
                $qr->where('created_at', '>', $start_day)
                    ->where('created_at', '<=', $end_day.' 23:59:59');
            }, 'complete'=>function($qr){
                $qr->where('status', 1);
            }, 'withdrawal'=>function($qr){
                $qr->where('status', 0);
            }]);
        $contents = $contents->first();

        $time_total = ViewVideo::select(DB::raw('SUM(time_view) as total_time'))
            ->where('created_at', '>', $start_day)
            ->where('created_at', '<=', $end_day.' 23:59:59')
            ->where('video_id', $id)
            ->groupBy('video_id')
            ->first();

        $viewlists = ViewVideo::select(DB::raw('COUNT(DISTINCT (case when status = 1 then user_id end)) as comp_u,COUNT(DISTINCT (case when status = 0 then user_id end)) as withx_u, COUNT(IF(status=1, 1 , NULL)) as comp ,COUNT(IF(status=0, 1 , NULL)) as withx ,COUNT(*) as total, SUM(time_view) as total_time ,DATE_FORMAT(created_at, "%Y-%m-%d %H") as time_list, DATE_FORMAT(created_at, "%H") as time_list_key'))
            ->where('created_at', '>', $start_day)
            ->where('created_at', '<=', $end_day.' 23:59:59')
            ->where('video_id', $id)
            ->groupBy('time_list')
            ->get();
        $datas = [];
        foreach ($viewlists as $key => $value) {
            $datas[$value->time_list_key] = $value;
        }
        $data_days = $dt = [];
        for ($i=0; $i < 24; $i++) { 
            $end = $i+1;
            if($i == 23) $end = 0;

            if($i<10) $k = '0'.$i;
            else $k = ''.$i;
            if($end<10) $k_e = '0'.$end;
            else $k_e = ''.$end;
            if(@$datas[$k]->total) {
                $view = @$datas[$k]->total;
            }else{
                $view = 0;
            }
            $dt['time'] = $k.':00-'.$k_e.':00';
            $dt['view'] = $view;
            $data_days[] = $dt;
        }
        return view('analytic.contentDetailDay', compact(['contents','data_days','id','time_total','start_day', 'end_day']));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function contentDetailDayTime(Request $request, $id, $day, $time)
    {
        if(!@$request->day || !@$request->time ) return back();
        $time_ar = explode('-', $time);
        $start_day = $day .' '.$time_ar[0].':00';
        $end_day = $day .' '.$time_ar[1].':00';
         $contents = Video::where('id', $id)
            ->withCount(['count_view', 'count_view_m'=>function($qr)use($start_day, $end_day){
                $qr->where('created_at', '>', $start_day)
                    ->where('created_at', '<=', $end_day.' 23:59:59');
            }, 'complete'=>function($qr){
                $qr->where('status', 1);
            }, 'withdrawal'=>function($qr){
                $qr->where('status', 0);
            }]);
        $contents = $contents->first();
        $datas = ViewVideo::with(['user'])->where('created_at', '>', $start_day)
            ->where('created_at', '<=', $end_day)
            ->where('video_id', $id)
            ->get();
        // dd($datas);
        return view('analytic.contentDetailDayTime', compact(['contents','datas','id','start_day', 'end_day']));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function contentDetailUser(Request $request, $id)
    {
        if(@$request->day) {
            $start_day = $request->day;
            $end_day = $request->day;
        }else{
            if(!@$request->start_day) {
                $start_day = date('Y-m-01');
            }else{
                $start_day = $request->start_day;
            }
            if(!@$request->end_day) {
                $end_day = date('Y-m-t');
            }else{
                $end_day = $request->end_day;
            }
        }

        
        $contents = Video::where('id', $id)->first();
        $user_vs = ViewVideo::with(['user'])
            ->where('created_at', '>', $start_day)
            ->where('created_at', '<=', $end_day.' 23:59:59')
            ->where('status', $request->status)
            ->where('video_id', $id)
            ->groupBy('user_id')
            ->get();
            // dd($user_vs);   
        return view('analytic.contentDetailUser', compact(['user_vs','contents','start_day', 'end_day']));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function contentDetailUserHistory(Request $request, $id)
    {
        $contents = Video::find($id);
        $viewlists = ViewVideo::with('user')->select('user_id',DB::raw('SUM(time_view) as total_time'))
        ->where('video_id', $id)
        ->groupBy('user_id')->get();
        return view('analytic.contentDetailUserHistory', compact(['viewlists','contents']));
        // dd($viewlists);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function contentDetailAccessHistory(Request $request, $id)
    {
        if(!@$request->start_day) {
            $start_day = date('Y-m-01');
        }else{
            $start_day = $request->start_day;
        }
        if(!@$request->end_day) {
            $end_day = date('Y-m-t');
        }else{
            $end_day = $request->end_day;
        }
        $contents = Video::find($id);
        $viewlists = ViewVideo::with('user')
        ->where('video_id', $id)
        ->where('created_at', '>=', $start_day)
        ->where('created_at', '<=', $end_day.' 23:59:59')
        ->paginate(50);
        return view('analytic.contentDetailAccessHistory', compact(['viewlists','contents']));
        // dd($viewlists);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function news(Request $request)
    {
        //
        if(!@$request->start_day) {
            $start_day = date('2016-01-01');
        }else{
            $start_day = $request->start_day;
        }
        if(!@$request->end_day) {
            $end_day = date('Y-m-t');
        }else{
            $end_day = $request->end_day;
        }

        // $news = Newc::withCount(['count_view','count_like', 'count_comment'])->paginate(20);
        $news = Newc::withCount(['count_view'=>function($qr)use($start_day, $end_day){
            $qr->where('created_at', '>=', $start_day)
               ->where('created_at', '<=', $end_day.' 23:59:59');
        }, 'count_comment'=>function($qr)use($start_day, $end_day){
            $qr->where('created_at', '>=', $start_day)
               ->where('created_at', '<=', $end_day.' 23:59:59');
        }])->has('count_view', '>',0);

        // $news = $news->where('count_view', '>', 0);
        $news = $news->orderBy('count_view_count', 'desc');
        if($request->view && $request->view >=0){
            if($request->view == 1) {
                $news = $news->orderBy('count_view_count', 'desc');
            }else{
                $news = $news->orderBy('count_view_count', 'asc');
            }
        }

        if($request->comment && $request->comment >=0){
            if($request->comment == 1) {
                $news = $news->orderBy('count_comment_count', 'desc');
            }else{
                $news = $news->orderBy('count_comment_count', 'asc');
            }
        }
        $news = $news->paginate(20);
        return view('analytic.news', compact(['news', 'start_day', 'end_day']));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function newsAccess(Request $request, $id)
    {
        if(!@$request->start_day) {
            $start_day = date('Y-m-01');
        }else{
            $start_day = $request->start_day;
        }
        if(!@$request->end_day) {
            $end_day = date('Y-m-t');
        }else{
            $end_day = $request->end_day;
        }
        $new = Newc::find($id);
        $viewlists = ViewNew::with('user')
        ->where('new_id', $id)
        ->where('created_at', '>=', $start_day)
        ->where('created_at', '<=', $end_day.' 23:59:59')
        ->paginate(50);
        return view('analytic.newsAccess', compact(['viewlists','new']));
        // dd($viewlists);
    }

    public function Ranking(Request $request)
    {
        if(!@$request->start_day) {
            $start_day = date('Y-m-01');
        }else{
            $start_day = $request->start_day;
        }
        if(!@$request->end_day) {
            $end_day = date('Y-m-t');
        }else{
            $end_day = $request->end_day;
        }
        // $b_day =  date('Y-m-d H:i:s', strtotime('-24 hours', strtotime($day)));
        $rankings = Event::select( 'id', 'seminar_id', 'category_event_id','theme_event_id', 'name','start_time')
            ->with(['category', 'theme'])
            ->withCount(['count_view'=>function($qr2)use($start_day, $end_day){
                $qr2->where('created_at', '>=', $start_day)
                    ->where('created_at', '<=', $end_day.' 23:59:59');
                    // ->where('created_at', '<=', $day)
                    // ->where('created_at', '>=', $b_day);
            }])
            ->where('seminar_id', $request->seminar_id);
            $rankings = $rankings->orderBy('count_view_count', 'DESC')->orderBy('start_time', 'DESC')->paginate(10);
        
        return view('analytic.ranking', compact(['rankings','start_day','end_day']));
    }

    public function analyticBanner(Request $request)
    {
        $categories = Category::with(['banners'=>function($qr){
            $qr->withCount(['banner_views']);
        }])->paginate(50);
        return view('analytic.banner', compact(['categories']));
    }

    public function analyticBannerView($id)
    {
        if(!@$request->start_day) {
            $start_day = date('Y-m-01');
        }else{
            $start_day = $request->start_day;
        }
        if(!@$request->end_day) {
            $end_day = date('Y-m-t');
        }else{
            $end_day = $request->end_day;
        }
        $banners = Banner::with(['video','category'])
        ->where('category_id', $id)
        ->withCount(['banner_views'=>function($qr)use($start_day, $end_day){
            $qr->where('created_at', '>', $start_day)
                ->where('created_at', '<=', $end_day.' 23:59:59');
        }])
        ->paginate(50);
        // dd($categories);
        
        return view('analytic.bannerView', compact(['banners','start_day','end_day']));
    }
    
    public static function exportF($titles=[], $datas=[], $filename='report.csv'){
        $cols = ['A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z','AA','AB','AC','AD','AE','AF','AG','AH','AI','AJ','AK','AL','AM','AN','AO','AP','AQ','AR','AS','AT','AU','AV','AW','AX','AY','AZ','BA','BB','BC','BD','BE','BF','BG','BH','BI','BJ','BK','BL','BM','BN','BO','BP','BQ','BR','BS','BT','BU','BV','BW','BX','BY','BZ'];
        if(count($datas)) {
          // dd($datas);
          $objPHPExcel = new \PHPExcel(); 
          foreach ($titles as $key => $title) {
            $objPHPExcel->getActiveSheet()->setCellValue($cols[$key].'1',$title); 
          }
          foreach ($datas as $keyR => $rows) {
            $r = $keyR+2;
            $c = 0;
            foreach ($rows as $keyC => $col) {
              $objPHPExcel->getActiveSheet()->setCellValue($cols[$c].$r,$col); 
              $c++;
            }
          }
          $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'CSV');
          header('Content-Encoding: UTF-8');
          header('Content-type: text/csv; charset=UTF-8');
          header("Content-Disposition: attachment; filename=".$filename);
          header("Pragma: no-cache");
          header("Expires: 0");
          header('Content-Transfer-Encoding: binary');
          echo "\xEF\xBB\xBF";
          $objWriter->save('php://output');
          exit();
        }
    }
    
}
