<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Category;
use App\Rss;
use App\RssUrl;
use App\Pr;
use App\Video;
use App\User;
use App\UserPush;


use DB;
use DOMDocument;

class RssController extends Controller
{
    // api 

    public function api_list_month(Request $request)
    {
        $user_id = get_user_id($request);
        $callback = function($query)use($user_id) {
            $query->where('user_id', $user_id);
        };
        $categories = Category::whereHas('user_category', $callback)->with(['user_category' => $callback])->where('publish', 0)->pluck('id');
        // $rss = Rss::All();
        $rss = Rss::select(DB::raw('DATE_FORMAT(date, "%Y-%m") as month'))
            ->whereIn('category_id', $categories)
            ->where('category_id', $request->association_id)
            ->where('date', '>=', date('Y-m-01'))
            ->where('publish', 1)
            ->groupBy('month')
            ->orderBy('month','ASC')
            ->get();
        if(count($rss)) {
            $data["statusCode"] = 0;
            $data["message"] = "";
            $data['data'] = $rss;  
        }else{
            $data["statusCode"] = 0;
            $data["message"] = "";
            $data['data'] = $rss;
        }
        
        return response()->json($data);
    }

    public function api_list_by_month(Request $request)
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

        $callback = function($query)use($user_id) {
            $query->where('user_id', $user_id);
        };
        $categories = Category::whereHas('user_category', $callback)->with(['user_category' => $callback])->where('publish', 0)->pluck('id');
        $s_date = $request->month.'-01';
        $e_date = $request->month.'-'.date('t',strtotime($s_date)).' 23:59:59';
        // return response()->json($e_date);
        // $rss = Rss::All();
        $rss = Rss::select('id','title','url','date','category_id')
            ->whereIn('category_id', $categories)
            ->where('category_id', $request->association_id)
            ->where('date','>', $s_date)
            ->where('date','<=', $e_date)
            ->where('publish', 1)
            ->orderBy('date','DESC')
            ->take(10)
            ->get();
        foreach ($rss as $keyr => $valuer) {
            if(in_array($valuer->id, $rss_p)) {
               $valuer->unread = 1;
            }else{
                $valuer->unread = 0;
            }
        }

        if(count($rss)) {
            $data["statusCode"] = 0;
            $data["message"] = "";
            $data['data'] = $rss;  
        }else{
            $data["statusCode"] = 0;
            $data["message"] = "";
            $data['data'] = $rss;
        }
        
        return response()->json($data);
    }
    
    public function api_list_limit(Request $request)
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

        $callback = function($query)use($user_id) {
            $query->where('user_id', $user_id);
        };
        $categories = Category::whereHas('user_category', $callback)->with(['user_category' => $callback])->where('publish', 0)->pluck('id');

        $rss = Rss::select('id','title','url','date','category_id')
            ->whereIn('category_id', $categories)
            ->where('category_id', $request->association_id)
            ->where('publish', 1)
            ->orderBy('date','DESC')
            ->take(3)
            ->get();
        if(count($rss)) {
            foreach ($rss as $keyr => $valuer) {
                if(in_array($valuer->id, $rss_p)) {
                   $valuer->unread = 1;
                }else{
                    $valuer->unread = 0;
                }
            }
        }
        
        $pr = Pr::select('id', 'title', 'video_id')
            ->whereIn('category_id', $categories)
            ->where('category_id', $request->association_id)
            ->where('publish', 1)
            ->orderBy('id','DESC')
            ->first();

        if($pr) {
            $pr->title = '[PR]'.$pr->title;
            $pr->date = Video::find($pr->video_id)->start_date;
            $videos = Video::select('id','title','discription','thumbnail','video','pdf','sponser', 'related_videos_1','related_videos_2','related_videos_3','type', 'view', 'ads_text', 'ads_url', 'ads_text_2', 'ads_url_2')
            ->with(['slider','related_1'=>function($qr1)use($user_id, $request){
                $qr1->withCount(['count_like', 'is_like'=>function($qr1)use($user_id){
                        $qr1->where('user_id', $user_id);
                    },'is_answer'=>function($qr2)use($user_id, $request){
                    $qr2->where('user_id', $user_id)->where('video_id', $request->id);
                }]);
            },'related_2'=>function($qr1)use($user_id, $request){
                $qr1->withCount(['count_like', 'is_like'=>function($qr1)use($user_id, $request){
                        $qr1->where('user_id', $user_id);
                    },'is_answer'=>function($qr2)use($user_id, $request){
                    $qr2->where('user_id', $user_id)->where('video_id', $request->id);
                }]);
            },'related_3'=>function($qr1)use($user_id, $request){
                $qr1->withCount(['count_like', 'is_like'=>function($qr1)use($user_id){
                        $qr1->where('user_id', $user_id);
                    },'is_answer'=>function($qr2)use($user_id, $request){
                    $qr2->where('user_id', $user_id)->where('video_id', $request->id);
                }]);
            }])
            ->where('publish',0)
            ->withCount(['count_like', 'is_like'=>function($qr1)use($user_id){
                        $qr1->where('user_id', $user_id);
                    },'is_answer'=>function($qr2)use($user_id, $request){
                    $qr2->where('user_id', $user_id)->where('video_id', $request->id);
                }])
            ->where('id',$pr->video_id)
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
            $pr->video = $videos;
            $rss[count($rss)] = $pr;
        }
        

        if(count($rss)) {
            $data["statusCode"] = 0;
            $data["message"] = "";
            $data['data'] = $rss;  
        }else{
            $data["statusCode"] = 0;
            $data["message"] = "";
            $data['data'] = $rss;
        }
        
        return response()->json($data);
    }

    
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function crontime()
    {
        //
        $crontime = DB::table('cronjobs')->where('id', 1)->first();
        // dd($crontime);

        $time = [];
        $minute = [];
        for ($i=0; $i < 24; $i++) { 
            if($i<10) $time[$i] = '0'.$i;
            else $time[$i] = $i;
        }
        for ($i=0; $i < 60; $i++) { 
            if($i<10) $minute[$i] = '0'.$i;
            else $minute[$i] = $i;
        }
        // dd($times);
        return view('rss.crontime', compact(['crontime', 'time', 'minute']));
    }

     public function postcrontime(Request $request)
    {
        //
        $crontime = DB::table('cronjobs')->where('id', 1)->update([
            'time'=>$request->time,
            'minute'=>$request->minute
        ]);
        return redirect(route('rss.index'));
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index( Request $request)
    {
        // 
        $categories = Category::pluck('category','id');
        $rss = Rss::where('id','>',0);
        if($request->category_id){
            $rss = $rss->where('category_id', $request->category_id);
        }
        $rss = $rss->orderBy('id', 'DESC')->paginate(20);
        // dd($rss);
        return view('rss.index',compact(['rss','categories']));
    }

    public function ajaxshow(Request $request)
    {
        // Rss::where('id','>',0)->update(['top'=>0]);
        $rss = Rss::find($request->id);
        $rss->publish = $request->publish;
        // $rss->date = date('Y-m-d H:i:s');
        $rss->save();
        echo $rss->publish; die();
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
        return view('rss.create', compact(['categories']));
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
        unset($data['_token']);
        $rs = Rss::insertGetId($data);

        if($rs) {
            $title = '学会からのお知らせが更新されました';
            $push_data = [
                'title' => $title,
                'body' => "",
                'type' => 1,
                'association_id' => $request->category_id
            ];
            $users = User::with(['devices'])
            ->whereHas('association', function($qr)use($data){
                $qr->where('category_id', $data['category_id']);
            })
            ->has('devices')
            ->get();
            if(count($users)) {
                foreach ($users as $key => $value) {
                    if($value->rss) {
                        $rss_p = json_decode($value->rss);
                    }else{
                        $rss_p = [];
                    }
                    $rss_p[] = $rs;
                    $datarss = ['rss'=>json_encode(array_merge($rss_p))];
                    User::where('id', $value->id)->update($datarss);
                    foreach ($value->devices as $keyd => $valued) {
                        notification_push($valued->token, $title, $push_data);
                    }
                }
            }
            return redirect(route('rss.index'));
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
        $rss = Rss::find($id);
        return view('rss.edit', compact(['categories','rss']));
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
        $data['block'] = 1;
        $rs = Rss::find($id)->update($data);
        if($rs) {
            return redirect(route('rss.index'));
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
        Rss::destroy($id);
        return redirect(route('rss.index'));
    }


    public function ajax_cate_url(Request $request)
    {
        //
        $rssurls_cate = RssUrl::where('id',$request->id)->select(['category_id'])->first();
        if($rssurls_cate->category_id){
            echo $rssurls_cate->category_id;
        }else{
            echo '';
        }
        

    }

        /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getrss()
    {
        //
        $categories = Category::pluck('category','id');
        $rssurls = RssUrl::pluck('name', 'id');
        return view('rss.getrss', compact(['categories','rssurls']));
    }

        /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function postrss(Request $request)
    {
        
        // $lines = file_get_contents($request->rss);

        $urlrss = RssUrl::find($request->rssurl);
        $arrContextOptions=array(
            "ssl"=>array(
                "verify_peer"=>false,
                "verify_peer_name"=>false,
            ),
        );  
        if($urlrss->url == 'http://160.16.183.94:8080/') return back();
        $doc = new DOMDocument();
        @$doc->load($urlrss->url);
        $items = $doc->getElementsByTagName( "item");

        if($items->length == 0){
            $items = $doc->getElementsByTagName( "entry");
        }
        if(!count($items)) {
        	$response = file_get_contents($urlrss->url, false, stream_context_create($arrContextOptions));
        	@$doc->loadXML($response);
        	$items = $doc->getElementsByTagName( "item");

	        if($items->length == 0){
	            $items = $doc->getElementsByTagName( "entry");
	        }
        }
        foreach ($items as $key => $item) {
            $tagtitle = $item->getElementsByTagName( "title" );
            if(count($tagtitle))  {
                $title = $tagtitle->item(0)->nodeValue;
            }

            $taglink = $item->getElementsByTagName( "link" );
            if(count($taglink))  {
                $link = $taglink->item(0)->nodeValue;
            }
            if($link==""){
                $link = $taglink->item(0)->getAttribute('href');
            }
            
            $tagpubDate = $item->getElementsByTagName( "pubDate" );
            if($tagpubDate->length == 0){
                $tagpubDate = $item->getElementsByTagName( "published");
            }
            $date = '0000-00-00 00:00:00';
            if(count($tagpubDate))  {
                $pubDate = $tagpubDate->item(0)->nodeValue;
                $date = date('Y-m-d H:i:s', strtotime($pubDate));
            }
           // dd($link);
            if(@$link) {
                // dd($link);
                $checks = Rss::where('url', @$link)->where('block', 1)->first();
                if(!$checks){
                    $ck = ['url'=>@$link];
                    $dl = [
                        'title' => @$title,
                        'category_id'=>$urlrss->category_id,
                        'date' => @$date,
                    ];
                    // dd($ck);
                    $rs = Rss::updateOrCreate($ck, $dl);
                
                }
            }
        }
        $n = date('Y-m-d').' 23:59:59';
        Rss::where('created_at', '>', date('Y-m-d'))
                ->where('created_at', '<=', $n)
                ->update(['publish'=> 1]);
                // dd('1');

        $rss_ctl = Rss::where('created_at', '>', date('Y-m-d'))
                ->where('created_at', '<=', date('Y-m-d').' 23:59:59')
                ->groupBy('category_id')
                ->get();
            if(count($rss_ctl)) {
                foreach ($rss_ctl as $key => $value) {
                    $title = '学会からのお知らせが更新されました';
                    $push_data = [
                        'title' => $title,
                        'body' => "",
                        'type' => 1,
                        'association_id' => $value->category_id
                    ];
                    $users = User::with(['devices'])
                    ->whereHas('association', function($qr)use($value){
                        $qr->where('category_id', $value->category_id);
                    })
                    ->has('devices')
                    ->get();
                    if(count($users)) {
                        foreach ($users as $keyp => $valuep) {
                            if($valuep->rss) {
                                $rss_p = json_decode($valuep->rss);
                            }else{
                                $rss_p = [];
                            }
                            $rss_p[] = $value->id;
                            $datarss = ['rss'=>json_encode(array_merge($rss_p))];
                            User::where('id', $valuep->id)->update($datarss);
                            if(@$valuep->devices) {
                                foreach ($valuep->devices as $keyd => $valued) {
                                    notification_push($valued->token, $title, $push_data);
                                }
                            }
                            
                        }
                    }
                }
            }
        return redirect(route('rss.index').'/?category_id='.$urlrss->category_id);
    }
    
        /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function cronjob()
    {
        
        // $lines = file_get_contents($request->rss);
        $crontime = DB::table('cronjobs')->where('id', 1)->first();
        $t_now = (int) date('H');
        $m_now = (int) date('i');
        // dd(  (int)$crontime->time );

        if($t_now ==  (int) $crontime->time && $m_now == (int)$crontime->minute) {
            $urlrssx = RssUrl::all();
            $arrContextOptions=array(
                "ssl"=>array(
                    "verify_peer"=>false,
                    "verify_peer_name"=>false,
                ),
            ); 
            foreach ($urlrssx as $key => $urlrss) {
            	if($urlrss->url == 'http://160.16.183.94:8080/') continue;
                $doc = new DOMDocument();
		        @$doc->load($urlrss->url);
		        $items = $doc->getElementsByTagName( "item");

		        if($items->length == 0){
		            $items = $doc->getElementsByTagName( "entry");
		        }
		        if(!count($items)) {
		        	$response = file_get_contents($urlrss->url, false, stream_context_create($arrContextOptions));
		        	@$doc->loadXML($response);
		        	$items = $doc->getElementsByTagName( "item");

			        if($items->length == 0){
			            $items = $doc->getElementsByTagName( "entry");
			        }
		        }
                foreach ($items as $key => $item) {
                    $tagtitle = $item->getElementsByTagName( "title" );
                    if(count($tagtitle))  {
                        $title = $tagtitle->item(0)->nodeValue;
                    }

                    $taglink = $item->getElementsByTagName( "link" );
                    if(count($taglink))  {
                        $link = $taglink->item(0)->nodeValue;
                    }
                    if($link==""){
                        $link = $taglink->item(0)->getAttribute('href');
                    }
                    
                    $tagpubDate = $item->getElementsByTagName( "pubDate" );
                    if($tagpubDate->length == 0){
                        $tagpubDate = $item->getElementsByTagName( "published");
                    }
                    $date = '0000-00-00 00:00:00';
                    if(count($tagpubDate))  {
                        $pubDate = $tagpubDate->item(0)->nodeValue;
                        $date = date('Y-m-d H:i:s', strtotime($pubDate));
                    }
                    if(@$link) {
                        $checks = Rss::where('url', @$link)->where('block', 1)->first();
                        if(!$checks){
                            $ck = ['url'=>@$link];
                            $dl = [
                                'title' => @$title,
                                'category_id'=>$urlrss->category_id,
                                // 'url'=>$link,
                                'date' => @$date,
                            ];
                            // dd($dl);
                        
                            $rs = Rss::updateOrCreate($ck, $dl);
                        }
                    }
                }
            }
            Rss::where('created_at', '>', date('Y-m-d'))
                ->where('created_at', '<=', date('Y-m-d').' 23:59:59')
                ->update(['publish'=> 1]);
            $rss_ctl = Rss::where('created_at', '>', date('Y-m-d'))
                ->where('created_at', '<=', date('Y-m-d').' 23:59:59')
                ->groupBy('category_id')
                ->get();
            if(count($rss_ctl)) {
                foreach ($rss_ctl as $key => $value) {
                    $title = '学会からのお知らせが更新されました';
                    $push_data = [
                        'title' => $title,
                        'body' => "",
                        'type' => 1,
                        'association_id' => $value->category_id
                    ];
                    $users = User::with(['devices'])
                    ->whereHas('association', function($qr)use($value){
                        $qr->where('category_id', $value->category_id);
                    })
                    ->has('devices')
                    ->get();
                    if(count($users)) {
                        foreach ($users as $keyp => $valuep) {
                            if($valuep->rss) {
                                $rss_p = json_decode($valuep->rss);
                            }else{
                                $rss_p = [];
                            }
                            $rss_p[] = $value->id;
                            $datarss = ['rss'=>json_encode(array_merge($rss_p))];
                            User::where('id', $valuep->id)->update($datarss);
                            if(@$valuep->devices) {
                                foreach ($valuep->devices as $keyd => $valued) {
                                    notification_push($valued->token, $title, $push_data);
                                }
                            }
                            
                        }
                    }
                }
            }
        }
    }
}
