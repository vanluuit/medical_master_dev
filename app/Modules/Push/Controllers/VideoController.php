<?php

namespace App\Modules\Push\Controllers;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Video;
use App\Category;
use App\Slider;
use App\Channel;
use App\Question;
use App\LikeVideo;
use App\UserQuestion;
use App\Answer;
use App\ViewVideo;
use App\StopVideo;
use App\User;
use App\VideoCategoryRela;
use App\PushUser;




use App\ChannelCategory;
use Validator;
use SocketIO;
use Illuminate\Support\Facades\Storage;

use DB;

class VideoController extends Controller
{
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
 
        $channels = Channel::whereHas('association', function($qr)use($category_id){
                $qr->where('category_id', $category_id);
            })->where('is_sponser', 1)->pluck('title','id');
        $channel_ids = Channel::whereHas('association', function($qr)use($category_id){
                $qr->where('category_id', $category_id);
            })->where('is_sponser', 1)->pluck('id')->toArray();

        $videos = Video::with(['channel'])->whereIn('channel_id', $channel_ids);
        if($request->channel_id) {
            $videos = $videos->whereHas('channel', function($query)use($request) {
                $query->where('channel_id', $request->channel_id);
            });
        }
        $videos = $videos->orderBy('id','DESC')->paginate(20);

        return view('Push::videos.index', compact(['videos','channels']));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        $user_id = session('user')['id'];
        $category_id = PushUser::find($user_id)->category_id;
        $channels = Channel::whereHas('association', function($qr)use($category_id){
                $qr->where('category_id', $category_id);
            })->where('is_sponser', 1)->pluck('title','id');
        $channel_ids = Channel::whereHas('association', function($qr)use($category_id){
                $qr->where('category_id', $category_id);
            })->where('is_sponser', 1)->pluck('id')->toArray();

        $videos = Video::with(['channel'])->whereIn('channel_id', $channel_ids)->pluck('title','id');
        // $videos = Video::pluck('title','id');
        // dd($videos);
        return view('Push::videos.create', compact(['channels','videos']));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // dd($output);
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
            return redirect(route('push.videos.create'))
                        ->withErrors($validator)
                        ->withInput();
        }

        $data = $request->all();
        // dd($data);
        unset($data['_token']);
        unset($data['thumbnail']);
        unset($data['thumbnail_detail']);
        unset($data['video_create']);
        unset($data['banner']);
        unset($data['slider']);
        if(@$data['related'][0]) {
            $data['related_videos_1'] = @$data['related'][0];
        }
        if(@$data['related'][1]) {
            $data['related_videos_2'] = @$data['related'][1];
        }
        if(@$data['related'][2]) {
            $data['related_videos_3'] = @$data['related'][2];
        }
        // $data['type'] = 1;
        unset($data['related']);
        // dd($data);
        $rs = Video::insertGetId($data);
        // $catehhs = ChannelCategory::where('channel_id',$request->channel_id)->get();
        // $client = new SocketIO('visoftech.com', 9001);
        // $client->setQueryParams([
        //     'token' => 'edihsudshuz',
        //     'id' => '8780',
        //     'cid' => '344',
        //     'cmp' => 2339
        // ]);
        // foreach ($catehhs as $key => $value) {
        //     $association[] = $value->category_id;
        // }
        // $data["statusCode"] = 0;
        // $data["message"] = ""; 
        // $data["data"] = [
        //     'type' => 0,
        //     'association' => $association,
        // ];
        // $rsu = json_encode($data);
        // $success = $client->emit('notify', $rsu);
        if($rs) {
            if($request->thumbnail){
                $path = '/storage/app/'.@$request->file('thumbnail')->store('TVpro/images');
                $vd = Video::find($rs);
                $vd->thumbnail = $path;
                $vd->save();
            }
            if($request->thumbnail_detail){
                $path = '/storage/app/'.@$request->file('thumbnail_detail')->store('TVpro/images');
                $vd = Video::find($rs);
                $vd->thumbnail_detail = $path;
                $vd->save();
            }
            if($request->banner){
                $path = '/storage/app/'.@$request->file('banner')->store('TVpro/images');
                $vd = Video::find($rs);
                $vd->banner = $path;
                $vd->save();
            }
            if($request->video_create){
                $partvd = $request->video_create->path();
                // dd($partvd);
                $folder = md5(date('Y-m-d H:i:s:u'));
                $store_p = 'TVpro/videos/'.$folder.'/';

                $path_up = '/storage/app/'.@$request->file('video_create')->store($store_p);
                $path = '/storage/app/TVpro/videos/'.$folder.'/playlist.m3u8';
                $video = str_replace('/storage/app/TVpro/videos/'.$folder.'/', '' , $path_up);
                $video = str_replace('/', '' , $video);
                $video = str_replace($folder, '' , $video);
                // 
                $cd = '/var/www/html/storage/app/'.$store_p;
                chmod($cd, 0777);

                $command = 'cd '.$cd.' ; /usr/local/bin/bin/ffmpeg -y -i '.$video.' -r 25 -g 25 -c:a libfdk_aac -b:a 128k -c:v libx264 -preset veryfast -b:v 1600k -maxrate 1600k -bufsize 800k -s 640x360 -c:a libfdk_aac -vbsf h264_mp4toannexb -flags -global_header -f ssegment -segment_list playlist.m3u8 -segment_list_flags +live-cache -segment_time 5 output-%04d.ts; sudo chmod -R 777 '.$cd.'; rm '.$cd.$video;
                exec($command." 2>&1", $output);

                $vd = Video::find($rs);
                $vd->video = $path;
                $vd->save();

            }
            if($request->pdf){
                $path = '/storage/app/'.@$request->file('pdf')->store('TVpro/pdf');
                $vd = Video::find($rs);
                $vd->pdf = $path;
                $vd->save();
            }
            if($request->slider){
                foreach (@$request->slider as $key => $image) {
                    $image = '/storage/app/'.$image->store('TVpro/images');
                    Slider::insert([
                        'video_id'=>$rs,
                        'image'=>$image
                    ]);
                }
            }
            $nofis = Video::find($rs);
            $hiephoi = ChannelCategory::where('channel_id', $nofis->channel_id)->pluck('category_id');
            // foreach ($hiephoi as $keyhh => $valuehh) {
            //     # code...
            // }
            $title = 'マイチャンネルに新たなコンテンツが追加されました。';
            $body = $nofis->title;
            $push_data = [
                'title' => $title,
                'body' => $body,
                'type' => 2,
                'association_id' => $hiephoi,
                'video_id' => $nofis->id,
                'content_type' => $nofis->type,
                'thumbnail_detail' => $nofis->thumbnail_detail
            ];

            $users = User::with(['devices'])
            ->whereHas('mypage_channel', function($qr)use($data){
                $qr->where('channel_id', $data['channel_id']);
            })
            ->has('devices')
            ->get();

            if(count($users)) {
                foreach ($users as $key => $value) {
                    if($value->content) {
                        $content_p = json_decode($value->content);
                    }else{
                        $content_p = [];
                    }
                    if (array_search($nofis->id, $content_p) == false && array_search($nofis->id, $content_p) !== 0) {
                        $content_p[] = $nofis->id;
                    }
                    
                    $datacontent = ['content'=>json_encode(array_merge($content_p))];
                    User::where('id', $value->id)->update($datacontent);

                    foreach ($value->devices as $keyd => $valued) {
                        foreach ($hiephoi as $keyhh => $valuehh) {
                            $push_data['association_id'] = $valuehh;
                            notification_push($valued->token, $title, $push_data, $body);
                        }
                        
                    }
                }
            }

            return redirect(route('push.videos.index'));
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
        $channels = Channel::pluck('title','id');
        $video = Video::with(['slider'])
        ->where('id',$id)
        ->first();
        // dd($video);
        $val = [];
        $val[] = $video->related_videos_1;
        $val[] = $video->related_videos_2;
        $val[] = $video->related_videos_3;
        $valj = json_encode($val);
        // dd($video);
        $videos = Video::where('channel_id', $video->channel_id)->where('id','<>',$video->id)->pluck('title','id');
        if($video->type==1){
            return view('Push::videos.edit', compact(['channels','video', 'videos','valj']));
        }elseif($video->type==2){
            return view('Push::videos.editpdf', compact(['channels','video', 'videos','valj']));
        }else{
            return view('Push::videos.editslider', compact(['channels','video', 'videos','valj']));
        }     
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
            return back()
                        ->withErrors($validator)
                        ->withInput();
        }

        $data = $request->all();
        // dd($data);
        unset($data['_token']);
        unset($data['thumbnail']);
        unset($data['thumbnail_detail']);
        unset($data['banner']);
        unset($data['video']);
        unset($data['slider']);
        unset($data['_method']);
        if(@$data['related'][0]) {
            $data['related_videos_1'] = @$data['related'][0];
        }else{
            $data['related_videos_1'] = 0;
        }
        if(@$data['related'][1]) {
            $data['related_videos_2'] = @$data['related'][1];
        }else{
            $data['related_videos_2'] = 0;
        }
        if(@$data['related'][2]) {
            $data['related_videos_3'] = @$data['related'][2];
        }else{
            $data['related_videos_3'] = 0;
        }
        unset($data['related']);
        $rs = Video::find($id)->update($data);
        if($rs) {
            $vd = Video::find($id);
            if($request->thumbnail){
                $thumbnail_u = $vd->thumbnail;
                $thumbnail_u = str_replace('/storage/app/', '', $thumbnail_u);
                $path = '/storage/app/'.@$request->file('thumbnail')->store('TVpro/images');
                $vd->thumbnail = $path;
                $vd->save();
                Storage::delete($thumbnail_u);
            }
            if($request->thumbnail_detail){
                $thumbnail_detail_u = $vd->thumbnail_detail;
                $thumbnail_detail_u = str_replace('/storage/app/', '', $thumbnail_detail_u);
                $path = '/storage/app/'.@$request->file('thumbnail_detail')->store('TVpro/images');
                $vd->thumbnail_detail = $path;
                $vd->save();
                Storage::delete($thumbnail_detail_u);
            }

            if($request->banner){
                $banner_u = $vd->banner;
                $banner_u = str_replace('/storage/app/', '', $banner_u);
                $path = '/storage/app/'.@$request->file('banner')->store('TVpro/images');
                $vd->banner = $path;
                $vd->save();
                Storage::delete($banner_u);
            }
            
            if($request->video_edit){
                if($vd->video != ""){
                    $rmf = 1;
                }else{
                    $rmf = 0;
                }
                $video_u = '/var/www/html'.$vd->video;
                $video_u = str_replace('/playlist.m3u8', '', $video_u);

                $folder = md5(date('Y-m-d H:i:s:u'));
                $store_p = 'TVpro/videos/'.$folder.'/';

                $path_up = '/storage/app/'.@$request->file('video_edit')->store($store_p);
                $path = '/storage/app/TVpro/videos/'.$folder.'/playlist.m3u8';
                $video = str_replace('/storage/app/TVpro/videos/'.$folder.'/', '' , $path_up);
                $video = str_replace('/', '' , $video);
                $video = str_replace($folder, '' , $video);
                // 
                $cd = '/var/www/html/storage/app/'.$store_p;
                chmod($cd, 0777);
                $command = 'cd '.$cd.' ; /usr/local/bin/bin/ffmpeg -y -i '.$video.' -r 25 -g 25 -c:a libfdk_aac -b:a 128k -c:v libx264 -preset veryfast -b:v 1600k -maxrate 1600k -bufsize 800k -s 640x360 -c:a libfdk_aac -vbsf h264_mp4toannexb -flags -global_header -f ssegment -segment_list playlist.m3u8 -segment_list_flags +live-cache -segment_time 5 output-%04d.ts; sudo chmod -R 777 '.$cd;
                if($rmf==1){
                    $command = $command.'; rm -rf '.$video_u;
                }
                // $command = 'cd '.$cd.' ; /usr/local/bin/bin/ffmpeg -y -i '.$video.' -r 25 -g 25 -c:a libfdk_aac -b:a 128k -c:v libx264 -preset veryfast -b:v 1600k -maxrate 1600k -bufsize 800k -s 640x360 -c:a libfdk_aac -vbsf h264_mp4toannexb -flags -global_header -f ssegment -segment_list playlist.m3u8 -segment_list_flags +live-cache -segment_time 5 output-%04d.ts; sudo chmod -R 777 '.$cd.'; rm -rf '.$video_u;
                exec($command." 2>&1", $output);

                $vd = Video::find($id)->update(['video'=> $path]);
                // $vd->video = $path;
                // $vd->save();

            }
            if($request->pdf){
                $pdf_u = $vd->pdf;
                $pdf_u = str_replace('/storage/app/', '', $pdf_u);
                $path = '/storage/app/'.@$request->file('pdf_edit')->store('TVpro/pdf');
                $vd->pdf = $path;
                $vd->save();
                Storage::delete($pdf_u);
            }

            if($request->slider){
                $slid = Slider::where('video_id', $id)->get();
                if(count($slid)) {
                    foreach ($slid as $key => $value) {
                        $slider_u = $value->slider;
                        $slider_u = str_replace('/storage/app/', '', $slider_u);
                        Storage::delete($slider_u);
                    }
                }
                Slider::where('video_id', $id)->delete();
                foreach (@$request->slider as $key => $image) {
                    $image = '/storage/app/'.$image->store('TVpro/images');
                    Slider::insert([
                        'video_id'=>$id,
                        'image'=>$image
                    ]);
                }
            }

            return redirect(route('push.videos.index'));
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
        LikeVideo::where('video_id', $id)->delete();
        $slid = Slider::where('video_id', $id)->get();
        if(count($slid)) {
            foreach ($slid as $key => $value) {
                $slider_u = $value->image;
                $slider_u = str_replace('/storage/app/', '', $slider_u);
                Storage::delete($slider_u);
            }
        }
        
        Slider::where('video_id', $id)->delete();
        $video = Video::find($id);
        if($video->thumbnail) {
            $thumbnail_u = $video->thumbnail;
            $thumbnail_u = str_replace('/storage/app/', '', $thumbnail_u);
            Storage::delete($thumbnail_u);
        }
        if($video->thumbnail_detail) {
            $thumbnail_detail_u = $video->thumbnail_detail;
            $thumbnail_detail_u = str_replace('/storage/app/', '', $thumbnail_detail_u);
            Storage::delete($thumbnail_detail_u);
        }
        if($video->video) {
            // $video_u = $video->video;
            // $video_u = str_replace('/storage/app/', '', $video_u);
            $video_u = '/var/www/html'.$video->video;
            $video_u = str_replace('/playlist.m3u8', '', $video_u);
            $command = 'rm -rf '.$video_u;
            exec($command." 2>&1", $output);

            // Storage::delete($video_u);
        }
        Video::destroy($id);
        ViewVideo::where('video_id',$id)->delete();
        return redirect(route('push.videos.index').'?channel_id='.@$request->channel_id);
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroyall(Request $request)
    {
        //
        if(@$request->delete) {
            foreach ($request->delete as $key => $id) {
                LikeVideo::where('video_id', $id)->delete();
                $slid = Slider::where('video_id', $id)->get();
                if(count($slid)) {
                    foreach ($slid as $key => $value) {
                        $slider_u = $value->image;
                        $slider_u = str_replace('/storage/app/', '', $slider_u);
                        Storage::delete($slider_u);
                    }
                }
                
                Slider::where('video_id', $id)->delete();
                $video = Video::find($id);
                if($video->thumbnail) {
                    $thumbnail_u = $video->thumbnail;
                    $thumbnail_u = str_replace('/storage/app/', '', $thumbnail_u);
                    Storage::delete($thumbnail_u);
                }
                if($video->thumbnail_detail) {
                    $thumbnail_detail_u = $video->thumbnail_detail;
                    $thumbnail_detail_u = str_replace('/storage/app/', '', $thumbnail_detail_u);
                    Storage::delete($thumbnail_detail_u);
                }
                if($video->video) {
                    // $video_u = $video->video;
                    // $video_u = str_replace('/storage/app/', '', $video_u);
                    $video_u = '/var/www/html'.$video->video;
                    $video_u = str_replace('/playlist.m3u8', '', $video_u);
                    $command = 'rm -rf '.$video_u;
                    exec($command." 2>&1", $output);

                    // Storage::delete($video_u);
                }
                Video::destroy($id);
                ViewVideo::where('video_id',$id)->delete();
            }
        }
        
        return redirect(route('push.videos.index').'?channel_id='.@$request->channel_id);
    }

    public function ajaxrelated(Request $request)
    {
        $videos = Video::where('channel_id', $request->id)->get();
        $vid = [];
        foreach ($videos as $key => $value) {
            $da['id'] = $value->id;
            $da['text'] = $value->title;
            $vid[] = $da;
        }
        echo json_encode($vid);die();
    }

    public function ajax_association(Request $request)
    {
        $channels = Channel::select('id','logo','title','discription','sponser','publish_date')
            ->whereHas('association', function($query)use($request) {
                $query->where('category_id', $request->id);
            })
            // ->whereDate('publish_date','<',date('Y-m-d H:i:s'))
            // ->where('category_channel_id',@$request->category_id)
            ->pluck('id');

        $videos = Video::whereIn('channel_id', $channels)->get();
        echo json_encode($videos);die();
    }
    
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function addpdf()
    {
        //
        $channels = Channel::pluck('title','id');
        $videos = Video::pluck('title','id');
        // dd($videos);
        return view('Push::videos.addpdf', compact(['channels','videos']));
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function addslider()
    {
        //
        $channels = Channel::pluck('title','id');
        $videos = Video::pluck('title','id');
        // dd($videos);
        return view('Push::videos.addslider', compact(['channels','videos']));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function question($id)
    {
        //
        $questions = UserQuestion::with(['user','question','answer'])->where('video_id',$id)->orderBy('created_at','DESC')->paginate(50);
        // dd($questions);
        return view('Push::videos.question', compact(['questions']));
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function questionedit($id, Request $request)
    {
        //
        $questions = Question::with(['answers'=>function($qr){
            $qr->orderBy("id", "ASC");
        }])->where('video_id',$id)->orderBy("id", "ASC")->get();
        if ($request->isMethod('post')) {
            // dd($request->all());
            foreach ($request->question_id as $kqid => $question_id) {
                if($question_id) {
                    Question::find($question_id)->update(["question"=>$request->question[$kqid]]);
                }else{
                    $question_id = Question::insertGetId([
                        'question'=>$request->question[$kqid],
                        'video_id'=>$id,
                    ]);
                }
                for ($i=0; $i < 5 ; $i++) { 
                    if($request->answer_id[$kqid][$i]) {
                        Answer::find($request->answer_id[$kqid][$i])->update(['answer'=>$request->answers[$kqid][$i]]);
                    }else{
                        Answer::insertGetId([
                            'answer'=>$request->answers[$kqid][$i],
                            'question_id'=>$question_id
                        ]);
                    }
                }
            }
            return redirect(route('push.videos.index'));
        }
        return view('Push::videos.questionedit', compact(['questions']));
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
        $category = Video::find($request->id);
        $category->publish = $request->publish;
        $category->save();
        echo $category->publish; die();
    }
    public function listByChannel(Request $request){
        $video = video::select(['id', 'title']);
        if($request->channel_id) {
            $video = $video->where('channel_id', $request->channel_id);
        }
        $video = $video->get();
        return json_encode($video);
    }
}
