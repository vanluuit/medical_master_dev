<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\SeminarController;
use App\Newc;
use App\User;
use App\CommentNew;
use App\UserNew;
use App\RssNew;

use App\CategoryNew;
use App\Category;
use App\ViewNew;
use App\NewDefault;
use App\NewBanner;


use Validator;
use DB;
use DOMDocument;
use DOMXPath;
use Illuminate\Support\Facades\Storage;

class NewController extends Controller
{
    // api
    
    public function api_list(Request $request)
    {
        // 
        $user_id = get_user_id($request);
        // 
        $news1 = Newc::with(['comments'=>function($qr)use($user_id){
                $qr->with('user')->withCount(['count_like', 'is_like'=>function($qr1)use($user_id){
                    $qr1->where('user_id', $user_id);
                }])->orderBy('id','DESC');
            },
        ])
        ->select('id','category_new_id','type','title','media','content','copyright','description','url','date','created_at')
        ->withCount(['count_mypage', 'is_mypage'=>function($qr1)use($user_id){
                $qr1->where('user_id', $user_id);
            },'count_like', 'is_like'=>function($qr1)use($user_id){
                $qr1->where('user_id', $user_id);
            }]);
        if($request->association_id){
            $news1 = $news1->where(function ($query)use($request)  {
                $query->orWhere('category_id', $request->association_id)
                    ->orWhere('category_id', 0);
            });
        }
        if($request->category_id){
            $news1 = $news1->where('category_new_id', $request->category_id);
        }
        $news1 = $news1->where('date', '<=', date('Y-m-d H:i:s'))->where('top', 1)->where('publish', 1);
        $news1 = $news1->orderBy('id', 'DESC')->take(1)->get();
        // return response()->json($news1);
        if($request->category_id==0){
            $news1 = [];
        }
        if(count($news1)) $limit = 19;
        else $limit = 20;
        $news2 = Newc::with(['comments'=>function($qr)use($user_id){
                $qr->with('user')->withCount(['count_like', 'is_like'=>function($qr1)use($user_id){
                    $qr1->where('user_id', $user_id);
                }])->orderBy('id','DESC');
            },
        ])
        ->select('id','category_new_id','type','title','media','content','copyright','description','url','date','created_at')
        ->withCount(['count_mypage', 'is_mypage'=>function($qr1)use($user_id){
                $qr1->where('user_id', $user_id);
            },'count_like', 'is_like'=>function($qr1)use($user_id){
                $qr1->where('user_id', $user_id);
            }]);
        if($request->association_id){
            $news2 = $news2->where(function ($query)use($request)  {
                $query->orWhere('category_id', $request->association_id)
                    ->orWhere('category_id', 0);
            });
        }
        if($request->category_id){
            $news2 = $news2->where('category_new_id', $request->category_id);
        }

        $news2 = $news2->where('date', '<=', date('Y-m-d H:i:s'))->where('top', 0)->where('publish', 1);
        $news2 = $news2->orderBy('date','DESC');
        $news2 = $news2->take($limit)->get();

        if($request->category_id !="" && $request->category_id == 0) {
            $categories = CategoryNew::pluck('category_name', 'id');
            $news2 = [];
            $ct = [];
            foreach ($categories as $key => $value) {
                if($key>0){
                    $New = Newc::with(['category','association']);
                    $New = $New->where('category_new_id', $key)->where('publish',1)->where('top',0);
                    if($request->association_id){
                        $New = $New->where(function ($query)use($request)  {
                            $query->orWhere('category_id', $request->association_id)
                                ->orWhere('category_id', 0);
                        });
                    }
                    $New = $New->where('date', '<=', date('Y-m-d H:i:s'))->orderBy('date', 'DESC');
                    $New = $New->first();
                    $ct[] = @$New->id;
                   
                }  
                
            }

            $news2 = Newc::with(['comments'=>function($qr)use($user_id){
                        $qr->with('user')->withCount(['count_like', 'is_like'=>function($qr1)use($user_id){
                            $qr1->where('user_id', $user_id);
                        }, 'is_own'=>function($qr1)use($user_id){
		                    $qr1->where('user_id', $user_id);
		                }])->orderBy('id','DESC');
                    },
                ])
                ->select('id','category_new_id','type','title','media','content','copyright','description','url','date','created_at')
                ->withCount(['count_mypage', 'is_mypage'=>function($qr1)use($user_id){
                        $qr1->where('user_id', $user_id);
                    },'count_like', 'is_like'=>function($qr1)use($user_id){
                        $qr1->where('user_id', $user_id);
                    }]);
                $news2 = $news2->whereIn('id',$ct);
                $news2 = $news2->orderBy('date','DESC');
                $news2 = $news2->take($limit)->get();
        }
        // $news = array_merge($news1, $news2);
        $news = [];
        foreach ($news1 as $key => $value) {
            $ccc = @$value->comments[0];
            unset($value->comments);
            $news1[$key]->comments = $ccc;
            $news1[$key]->qr = 0;
            $news[] = $news1[$key];
        }
        foreach ($news2 as $key => $value) {
            if(count($news) == 3) {
                if($this->get_banner($request)) {
                    $news[] = $this->get_banner($request);
                }
                
            }
            $ccc = @$value->comments[0];
            unset($value->comments);
            $news2[$key]->comments = $ccc;
            $news2[$key]->qr = 0;
            $news[] = $news2[$key];
        }
        if($news) {
            $data["statusCode"] = 0;
            $data["message"] = "";
            $data['data'] = $news;  
            // $data['banner'] = $this->get_banner($request);
        }else{
            $data["statusCode"] = 0;
            $data["message"] = "";
            $data['data'] = $news;

        }
        
        
        return response()->json($data);
    }


    public function api_detail(Request $request)
    {
        $user_id = get_user_id($request);
        $news = Newc::with(['comments'=>function($qr)use($user_id){
                $qr->with('user')->withCount(['count_like', 'is_like'=>function($qr1)use($user_id){
                    $qr1->where('user_id', $user_id);
                }, 'is_own'=>function($qr1)use($user_id){
                    $qr1->where('user_id', $user_id);
                }])->orderBy('id','DESC');
            },
        ])->select('id','category_new_id','type','title','media','content','copyright','description','url','date','created_at')
        ->withCount(['count_mypage', 'is_mypage'=>function($qr1)use($user_id){
                        $qr1->where('user_id', $user_id);
                    },'count_like', 'is_like'=>function($qr1)use($user_id){
                        $qr1->where('user_id', $user_id);
                    }]);
        // if($request->category_id){
        //     $news = $news->where('id', $request->category_id);
        // }
        $news = $news->where('id', $request->id)->first();
        $rep = route('login').'/laravel-filemanager/';
        $ct = str_replace('/public/laravel-filemanager/', '/laravel-filemanager/', $news->content);
        $ct = str_replace('/laravel-filemanager/', $rep, $ct);
        // $ct = str_replace('style="margin-left: 5.25pt; text-indent: -5.25pt; mso-char-indent-count: -.5;"', '', $ct);
        $ct = '<div> <style type="text/css">p{margin: 0 0 0 0; line-height: 1.5;text-align: justify;margin-left: 0px; text-indent: 0!important; mso-char-indent-count: 0;} img { max-width: 100%; margin: 0 auto; padding: 3px; } p.MsoNormal{margin: 0 auto;margin-left: 0px;text-indent: 0!important; mso-char-indent-count: 0;}</style>'.$ct."</div>";
        // $ct = str_replace('"', "'", $ct);
        // return response()->json($ct);
        $news->content = $ct;
        $ccc = [];
        for ($i=0; $i < 10; $i++) { 
            if(@$news->comments[$i]) {
                $ccc[] = $news->comments[$i];
            }
        }
        unset($news->comments);
        $news->comments = $ccc;
        if($news) {
            $data["statusCode"] = 0;
            $data["message"] = "";
            $data['data'] = $news;  
        }else{
            $data["statusCode"] = 0;
            $data["message"] = "";
            $data['data'] = $news;
        }
        return response()->json($data);
    }


    public function api_mypage_set(Request $request)
    {
        $user_id = get_user_id($request);
        $check = DB::table('user_news')->where('new_id', $request->id)->where('user_id', $user_id)->first();
        if($check) {
            $rs = DB::table('user_news')->where('new_id', $request->id)->where('user_id', $user_id)->delete();
        }else{
            $rs = DB::table('user_news')->insert([
                'new_id' => $request->id,
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

    public function api_add_comment(Request $request)
    {
        $validator = Validator::make(
            $request->all(), 
            [
                'id' => 'required',
                'comment' => 'required',
            ],
            [
                'id.required' => 'idが間違っています。',
                'comment.required' => 'commentが間違っています。',
            ]
        );
        if ($validator->fails()) {
            $data["statusCode"] = 1;
            $data["message"] = "Error occurred when comment";
            $data['validator'] = $validator->errors();
            return response()->json($data);
        }
        $user_id = get_user_id($request);

        $cm = new CommentNew;
        $cm->user_id = $user_id;
        $cm->new_id = $request->id;
        $cm->comment = $request->comment;
        $rs = $cm->save();
        if($rs) {
            $data["statusCode"] = 0;
            $data["message"] = ""; 
        }else{
            $data["statusCode"] = 1;
            $data["message"] = "error";
        }
        return response()->json($data);
    }
    
    public function api_edit_comment(Request $request)
    {
        $validator = Validator::make(
            $request->all(), 
            [
                'id' => 'required',
                'comment' => 'required',
            ],
            [
                'id.required' => 'idが間違っています。',
                'comment.required' => 'commentが間違っています。',
            ]
        );
        if ($validator->fails()) {
            $data["statusCode"] = 1;
            $data["message"] = "Error occurred when comment";
            $data['validator'] = $validator->errors();
            return response()->json($data);
        }
        $user_id = get_user_id($request);

        $check = CommentNew::where('id', $request->id)->where('user_id', $user_id);
        if($check->first()){
            $check->update([
                    'comment'=>$request->comment
                ]);
            $data["statusCode"] = 0;
            $data["message"] = ""; 
        }else{
            $data["statusCode"] = 1;
            $data["message"] = "error";
        }
        return response()->json($data);
    }

    public function api_delete_comment(Request $request)
    {
        $validator = Validator::make(
            $request->all(), 
            [
                'id' => 'required',
            ],
            [
                'id.required' => 'idが間違っています。',
            ]
        );
        if ($validator->fails()) {
            $data["statusCode"] = 1;
            $data["message"] = "Error occurred when delete";
            $data['validator'] = $validator->errors();
            return response()->json($data);
        }
        $user_id = get_user_id($request);

        $check = CommentNew::where('id', $request->id)->where('user_id', $user_id)->first();
        if($check){
            CommentNew::destroy($request->id);
            DB::table('like_comments')->where('comment_id',$request->id)->delete();
            $data["statusCode"] = 0;
            $data["message"] = ""; 
        }else{
            $data["statusCode"] = 1;
            $data["message"] = "error";
        }
        return response()->json($data);
    }
    public function api_comment_like_set(Request $request)
    {
        $user_id = get_user_id($request);
        $check = DB::table('like_comments')->where('comment_id', $request->id)->where('user_id', $user_id)->first();
        if($check) {
            $rs = DB::table('like_comments')->where('comment_id', $request->id)->where('user_id', $user_id)->delete();
        }else{
            $rs = DB::table('like_comments')->insert([
                'comment_id' => $request->id,
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

    public function api_like_set(Request $request)
    {
        $user_id = get_user_id($request);
        $check = DB::table('like_news')->where('new_id', $request->id)->where('user_id', $user_id)->first();
        if($check) {
            $rs = DB::table('like_news')->where('new_id', $request->id)->where('user_id', $user_id)->delete();
        }else{
            $rs = DB::table('like_news')->insert([
                'new_id' => $request->id,
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


    public function api_mypage_list(Request $request)
    {
        $user_id = get_user_id($request);
        $news = Newc::with(['comments'=>function($qr)use($user_id){
                $qr->with('user')->withCount(['count_like', 'is_like'=>function($qr1)use($user_id){
                    $qr1->where('user_id', $user_id);
                }])->orderBy('id','DESC');
            },
        ])
        ->select('id','category_new_id','type','title','media','content','copyright','description','url','created_at')
        ->withCount(['count_mypage', 'is_mypage'=>function($qr1)use($user_id){
                $qr1->where('user_id', $user_id);
            },'count_like', 'is_like'=>function($qr1)use($user_id){
                $qr1->where('user_id', $user_id);
            }])
        ->whereHas('is_mypage', function($qrh)use($user_id){
            $qrh->where('user_id', $user_id);
        });
        
        if($request->search){
            $news = $news->where('title','Like' ,'%'.$request->search.'%');
        }

        $news = $news->take(10)->get();
        if(count($news)){
            foreach ($news as $key => $value) {
                $ccc = @$value->comments[0];
                unset($value->comments);
                $news[$key]->comments = $ccc;
            }
        }
        
        if($news) {
            $data["statusCode"] = 0;
            $data["message"] = "";
            $data['data'] = $news;  
        }else{
            $data["statusCode"] = 0;
            $data["message"] = "";
            $data['data'] = $news;
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
        // $news = Newc::select('id', 'content')->where('type', 0)->get();
        // // dd($news);  
        // foreach ($news as $key => $value) {
        //     // $str = str_replace('http://3.18.30.135/laravel-filemanager/photos/', '/laravel-filemanager/photos/', $value->content);
        //     $str = str_replace('/laravel-filemanager/', 'http://d2b6hk3qq61u1u.cloudfront.net/laravel-filemanager/', $value->content);
        //     Newc::find($value->id)->update(['content'=>$str]);
            
        // }
        //
        // $news = Newc::select('id', 'url_check')->get();
        // // dd($news);  
        // foreach ($news as $key => $value) {
        //      $urlc = explode('?', $value->url_check)[0];
        //      Newc::where('id', $value->id)->update(['url_check'=>$urlc]);
        // }     
        // die(); 
        // notification('03df25c845d460bcdad7802d2vf6fc1dfde97283bf75cc993eb6dca835ea2e2f', 'test push');

        //$news_s = $news_b = Newc::select('id', 'title')->get();
        $dtcs = [];
        // foreach ($news_b as $key => $value) {
        //     foreach ($news_s as $key1 => $value1) {
        //         if($value->id != $value1->id && $value->title == $value1->title && $value->id < $value1->id) {
        //             $dtx = [];
        //             $dtx['id'] = $value->id;
        //             $dtx['id1'] = $value1->id;
        //             $dtcs[] = $dtx;
        //         }
        //     }
        // }

        $categories = CategoryNew::pluck('category_name', 'id');
        // dd($request->category_id);
        $News = Newc::with(['category','association'])
        ->withCount(['comments']);

        if($request->category_id && $request->category_id !="") {
            $News = $News->where('category_new_id', $request->category_id);
        }
        

        $News = $News->orderBy('date', 'DESC');

        
        $News = $News->paginate(20);
        
        // dd($categories);
        if($request->category_id !="" && $request->category_id == 0) {
            $News = [];
            $ct = [];
            foreach ($categories as $key => $value) {
                if($key>0){
                    $New = Newc::with(['category','association']);
                    $New = $New->where('category_new_id', $key)->where('publish',1);
                    $New = $New->orderBy('date', 'DESC');
                    $New = $New->first();
                    $ct[] = @$New->id;
                   
                }  
                $News = Newc::with(['category','association'])
                ->whereIn('id', $ct)->orderBy('date', 'DESC')->paginate(20);
            }
        }

        return view('news.index',compact(['News','categories', 'dtcs']));
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function list_comment_by_news($id, Request $request)
    {
        //
        $News = Newc::where('id', $id)->first();
        $comments = CommentNew::where('new_id', $id)->paginate(20);
        return view('news.comments',compact(['comments','News','id']));
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function comment_destroy($id, $new)
    {
        //
        CommentNew::destroy($id);
        DB::table('like_comments')->where('comment_id',$id)->delete();
        return redirect(route('news.comments_news', $new));
    }

     /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function ajaxtop(Request $request)
    {
        $new = Newc::find($request->id);
        Newc::where('category_new_id',$new->category_new_id)->update(['top'=>0]);
        $new->top = $request->sh;
        $new->save();
        echo $request->sh; die();
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
        $new = Newc::find($request->id);
        $new->publish = $request->publish;
        // $new->date = date('Y-m-d H:i:s');
        $new->save();
        echo $new->publish; die();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        // $news = Newc::where('type', 1)->get();
        // foreach ($news as $key => $value) {
        //     $dataup = ['url_check'=>$value->url];
        //     Newc::where('id', $value->id)->update($dataup);
        // }
        $categories = CategoryNew::where('id','>',0)->pluck('category_name','id');
        $cate = Category::pluck('category','id')->toArray();
        array_unshift($cate, '全ての学会');
        return view('news.create', compact(['categories','cate']));
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
        $data['date'] = date('Y-m-d H:i:s');
        $rs = Newc::insertGetId($data);
        if($rs) {
            if($request->media){
                $path = '/storage/app/'.@$request->file('media')->store('news');
                $new = Newc::find($rs);
                $new->media = $path;
                $new->save();
            }
            return redirect(route('news.index'));
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
         $News = Newc::find($id);
          $cate = Category::pluck('category','id');
         $categories = CategoryNew::pluck('category_name','id');
        return view('news.show', compact(['News','categories','cate']));
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
        $News = Newc::find($id);
        $cate = Category::pluck('category','id')->toArray();
        array_unshift($cate, '全ての学会');

        $categories = CategoryNew::where('id','>',0)->pluck('category_name','id');
        // dd($categories);
        return view('news.edit', compact(['News','categories','cate']));
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
        unset($data['media']);
        $data['block'] = 1;
        $rs = Newc::find($id)->update($data);
        if($rs) {
            if($request->media){
                $news = Newc::find($id);
                $media_u = $news->media;
                $media_u = str_replace('/storage/app/', '', $media_u);
                $path = '/storage/app/'.@$request->file('media')->store('news');
                $news->media = $path;
                $news->save();
                if($news->type == 0) {
                    Storage::delete($media_u);
                }
            }
            return redirect(route('news.edit', $id));
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
        $news = Newc::with(['comments'])->where('id', $id)->first();
        if(@$news->media) {
            $media = $news->media;
            $media = str_replace('/storage/app/', '', $media);
            if(@$news->type == 0) {
            Storage::delete($media);
            }
        }
        if(@$news->comments) {
            foreach (@$news->comments as $key_cm => $value_cm) {
                DB::table('like_comments')->where('comment_id',$value_cm->id)->delete();
            }
        }
        CommentNew::where('new_id',$news->id)->delete();
        UserNew::where('new_id',$news->id)->delete();
        DB::table('like_news')->where('new_id',$news->id)->delete();
        Newc::destroy($id);
        viewNew::where('new_id',$id)->delete();
        return redirect(route('news.index'));
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroyall(Request $request)
    {
        if(@$request->delete) {
            foreach ($request->delete as $key => $id) {

                $news = Newc::with(['comments'])->where('id', $id)->first();
                dd($news);
                if(@$news->media) {
                    $media = $news->media;
                    $media = str_replace('/storage/app/', '', $media);
                    // Storage::delete($media);
                    if(@$news->type == 0) {
                    Storage::delete($media);
                    }
                }
                if(@$news->comments) {
                    foreach (@$news->comments as $key_cm => $value_cm) {
                        DB::table('like_comments')->where('comment_id',$value_cm->id)->delete();
                    }
                }
                CommentNew::where('new_id',$news->id)->delete();
                UserNew::where('new_id',$news->id)->delete();
                DB::table('like_news')->where('new_id',$news->id)->delete();
                Newc::destroy($id);
                viewNew::where('new_id',$id)->delete();
            }
        }
        
        return redirect(route('news.index').'?category_id='.@$request->category_id);
    }
    
     public function ajax_cate_url(Request $request)
    {
        //
        $rssurls_cate = RssNew::where('id',$request->id)->select(['category_new_id'])->first();
        echo $rssurls_cate->category_new_id;

    }

    public function ajax_cate_url_hh(Request $request)
    {
        //
        $rssurls_cate = RssNew::where('id',$request->id)->select(['category_id'])->first();
        if($rssurls_cate->category_id >= 0){
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
        // $rss = RssNew::all();
        // dd($rss);
        $categories = CategoryNew::where('id','>',0)->pluck('category_name','id');
        $cate = Category::pluck('category','id')->toArray();
        array_unshift($cate, '全ての学会');
        $rssurls = RssNew::pluck('name', 'id');
        return view('news.getrss', compact(['categories','cate','rssurls']));
    }

        /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function innerHTML($el) {
        $doc = new DOMDocument();
        $doc->appendChild($doc->importNode($el, TRUE));
        $html = trim($doc->saveHTML($doc->documentElement));
        $html = html_entity_decode($html,ENT_HTML5, 'UTF-8');
        $tag = $el->nodeName;
        $tag_r = preg_replace('@^<' . $tag . '[^>]*>|</' . $tag . '>$@', '', $html);
        $tagshow = str_replace('<br>', "\r\n", $tag_r);
        $tagshow = str_replace('<br >', "\r\n", $tagshow);
        $tagshow = str_replace('<br/>', "\r\n", $tagshow);
        $tagshow = str_replace('<br />', "\r\n", $tagshow);
        return $tagshow;
    }

    public function postrss(Request $request)
    {
        //
        $rss = RssNew::find($request->rss);
        // $lines = file_get_contents($rss);
        $doc = new DOMDocument();
        // if(!@$doc->load($rss)){
        //  die();
        // }
        @$doc->load($rss->url);
        $lines = file_get_contents($rss->url);
        // $lines = str_replace('&copy;', '&#169;', $lines);
        // @$doc->loadXML($lines);
        // dd($doc);
	    $items = $doc->getElementsByTagName("item");
	    // dd($items);
        if($items->length == 0){
        	$items = $doc->getElementsByTagName( "entry");
        }
        // dd($items);

        foreach ($items as $key => $item) {
            $copyright = $description = $link = $title = $img = '';
            $tagCopyright = $doc->getElementsByTagName("copyright");
            if(count($tagCopyright))  {
                $copyright = $tagCopyright->item(0)->nodeValue;
            }
            $tagCopyrightitem = $item->getElementsByTagName("copyright");
            if(count($tagCopyrightitem))  {
                $copyright = $tagCopyrightitem->item(0)->nodeValue;
            }
            if(!empty($rss->copyright)) {
                $copyright = $rss->copyright;
            }

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
            // dd($link);
            
            $tagdescription = @$item->getElementsByTagName( "description" );
            if(count($tagdescription))  {
                $description = @$tagdescription->item(0)->nodeValue;
            }else{
            	$tagdescription = @$item->getElementsByTagName( "content");
            	$description = @$tagdescription->item(0)->nodeValue;
            }
            
            $date = '0000-00-00 00:00:00';
            $tagpubDate = $item->getElementsByTagName( "pubDate" );
            if(count($tagpubDate))  {
                $pubDate = $tagpubDate->item(0)->nodeValue;
                $date = date('Y-m-d H:i:s', strtotime($pubDate));
            }else  {
            	$tagpubDate = $item->getElementsByTagName( "published" );
                $pubDate = $tagpubDate->item(0)->nodeValue;
                $date = date('Y-m-d H:i:s', strtotime($pubDate));
            }
            // dd($date);
            
            $tagimage = $item->getElementsByTagName("img");
            $tagimage1 = $item->getElementsByTagName("image");
            $enclosure = $item->getElementsByTagName("enclosure");
            $tagimage2 = [];
            if(count($enclosure)) {
                if($enclosure->item(0)->getAttribute('type') == 'image/jpeg') {
                    $tagimage2 = $enclosure;
                }
            }
            // $tagimage3 = $item->getElementsByTagName("media");
            $tagimage3 = $item->getElementsByTagNameNS('http://search.yahoo.com/mrss/', 'thumbnail');

            if(count($tagimage))  {
                $img = $tagimage->item(0)->getAttribute('src');
            }elseif(count($tagimage1)){
                $img = $tagimage1->item(0)->nodeValue;
            }elseif(count($tagimage2)){
                $img = $tagimage2->item(0)->getAttribute('url');
            }elseif(count($tagimage3)){
                $img = $tagimage3->item(0)->getAttribute('url');
            }else{
                $img = $rss->thumbnail;
            }

            if(@$link) {
                $checks = Newc::where('url_check', @$link)->where('block', 1)->first();
                if(!$checks){
                    $url = $link;
                    if($rss->param != ""){
                        $url = $link.$rss->param;
                    }
                    $ck = ['url_check'=>@$link];
                    $dl = [
                        'type' => 1,
                        'url'=>@$url,
                        'title' => @$title,
                        'media' => @$img,
                        'description' => @$description,
                        'date' => @$date,
                        'category_new_id' => @$rss->category_new_id,
                        'category_id' => @$rss->category_id,
                        'copyright' =>@$copyright,
                        'rss_new_id' =>$rss->id,
                    ];
                    // dd($dl);
                    $rs = Newc::updateOrCreate($ck, $dl);
                }
            }
            
        }
        return redirect(route('news.index').'/?category_id='.$rss->category_new_id);
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function crontime()
    {
        //
        $crontime = DB::table('cronjobs')->where('name', 'new')->get();
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
        return view('news.crontime', compact(['crontime', 'time', 'minute']));
    }

     public function postcrontime(Request $request)
    {
        //
        $crontime = DB::table('cronjobs')->insert([
            'name'=>'new',
            'time'=>$request->time,
            'minute'=>$request->minute
        ]);
        return redirect(route('new.crontime'));
    }
    public function crontimedelete($id)
    {
        //
        // dd($id);
        $crontime = DB::table('cronjobs')->where('id', $id)->delete();
        return redirect(route('new.crontime'));
    }


    public function cronjob(Request $request)
    {
        //
        $t_now = date('H');
        $m_now = date('i');
        $crontime = DB::table('cronjobs')
            ->where('name', 'new')
            ->where('time', $t_now)
            ->where('minute', $m_now)
            ->first();
        
        if($crontime) {

            $rsss = RssNew::all();
            foreach ($rsss as $keyr => $rss) {
                // $lines = file_get_contents($rss);
                $doc = new DOMDocument();
                // if(!@$doc->load($rss)){
                //  die();
                // }
                @$doc->load($rss->url);

             
                $items = $doc->getElementsByTagName( "item");
                if($items->length == 0){
		        	$items = $doc->getElementsByTagName( "entry");
		        }
                // $tagCopyright = $doc->getElementsByTagName( "copyright");
                // $copyright = $rss->copyright;
                // if(count($tagCopyright))  {
                //     $copyright = $tagCopyright->item(0)->nodeValue;
                // }
                foreach ($items as $key => $item) {
                    $copyright = $description = $link = $title = $img = '';
                    $tagCopyright = $doc->getElementsByTagName("copyright");
                    if(count($tagCopyright))  {
                        $copyright = $tagCopyright->item(0)->nodeValue;
                    }
                    $tagCopyrightitem = $item->getElementsByTagName("copyright");
                    if(count($tagCopyrightitem))  {
                        $copyright = $tagCopyrightitem->item(0)->nodeValue;
                    }
                    if(!empty($rss->copyright)) {
                        $copyright = $rss->copyright;
                    }

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

                    $tagdescription = @$item->getElementsByTagName( "description" );
                    if(count($tagdescription))  {
                        $description = @$tagdescription->item(0)->nodeValue;
                    }else{
		            	$tagdescription = @$item->getElementsByTagName( "content");
		            	$description = @$tagdescription->item(0)->nodeValue;
		            }
                    
                    $date = '0000-00-00 00:00:00';
                    $tagpubDate = $item->getElementsByTagName( "pubDate" );
                    if(count($tagpubDate))  {
                        $pubDate = $tagpubDate->item(0)->nodeValue;
                        $date = date('Y-m-d H:i:s', strtotime($pubDate));
                        
                    }else  {
		            	$tagpubDate = $item->getElementsByTagName( "published" );
		                $pubDate = $tagpubDate->item(0)->nodeValue;
		                $date = date('Y-m-d H:i:s', strtotime($pubDate));
		            }
                    
                    $tagimage = $item->getElementsByTagName("img");
                    $tagimage1 = $item->getElementsByTagName("image");
                    $enclosure = $item->getElementsByTagName("enclosure");
                    $tagimage2 = [];
                    if(count($enclosure)) {
                        if($enclosure->item(0)->getAttribute('type') == 'image/jpeg') {
                            $tagimage2 = $enclosure;
                        }
                    }
                    // $tagimage3 = $item->getElementsByTagName("media");
                    $tagimage3 = $item->getElementsByTagNameNS('http://search.yahoo.com/mrss/', 'thumbnail');

                    if(count($tagimage))  {
                        $img = $tagimage->item(0)->getAttribute('src');
                    }elseif(count($tagimage1)){
                        $img = $tagimage1->item(0)->nodeValue;
                    }elseif(count($tagimage2)){
                        $img = $tagimage2->item(0)->getAttribute('url');
                    }elseif(count($tagimage3)){
                        $img = $tagimage3->item(0)->getAttribute('url');
                    }else{
                        $img = $rss->thumbnail;
                    }

                    

                    if(@$link) {
                        $checks = Newc::where('url_check', @$link)->where('block', 1)->first();
                        if(!$checks){
                            $url = $link;
                            if($rss->param != ""){
                                $url = $link.$rss->param;
                            }
                            $ck = ['url_check'=>@$link];
                            $dl = [
                                'type' => 1,
                                'url'=>@$url,
                                'title' => @$title,
                                'media' => @$img,
                                'description' => @$description,
                                'date' => @$date,
                                'category_new_id' => @$rss->category_new_id,
                                'category_id' => @$rss->category_id,
                                'copyright' =>@$copyright,
                                'rss_new_id' =>$rss->id,
                                // 'publish' =>1,
                            ];
                            // dd($dl);
                            $rs = Newc::updateOrCreate($ck, $dl);
                        }
                    }
                    
                }
            }
            // $cate = CategoryNew::all();
            // foreach ($cate as $keyc => $valuec) {
            //     Newc::where('type', 1)->where('created_at', '>', date('Y-m-d'))
            //     ->where('created_at', '<=', date('Y-m-d').' 23:59:59')
            //     ->where('category_new_id', $valuec->id)
            //     ->update(['publish'=> 1]);
            // }
        }
    }

    public function crondelete(Request $request)
    {
    	$day3 = Date('Y-m-d', strtotime("-3 days"));
    	$weeks2 = Date('Y-m-d', strtotime("-2 weeks"));
        $news = Newc::withCount(['comments'])
        ->where('rss_new_id', 0)
        ->where('type', 1)
        ->where('date', '<', $day3)
        ->pluck('id');
        Newc::destroy($news);

       $news3d = Newc::withCount(['comments'])
        ->where('rss_new_id', '>', 0)
        // ->whereHas('rssmews', function($qr){
        //     $qr->where('destroy', 0);
        // })
        ->where('date', '<=', $day3)
        ->orderBy('date', 'ASC')
        ->get();
        
        $new_d = [];
        foreach ($news3d as $key => $value) {
            if($value->comments_count == 0) {
                $new_d[] = $value;
            }
        }
        Newc::destroy($new_d);

        $new2w = Newc::withCount(['comments'])
        ->where('rss_new_id', '>', 0)
        ->whereHas('rssmews', function($qr){
            $qr->where('destroy', 1);
        })
        ->where('date', '<=', $weeks2)
        ->pluck('id');
        Newc::destroy($new2w);
    }
    public function list_cron_delete(Request $request)
    {
        $day3 = Date('Y-m-d', strtotime("-3 days"));
        $weeks2 = Date('Y-m-d', strtotime("-2 weeks"));
        $news3d = Newc::withCount(['comments'])
        ->where('rss_new_id', '>', 0)
        // ->whereHas('rssmews', function($qr){
        //     $qr->where('destroy', 0);
        // })
        ->where('date', '<=', $day3)
        ->orderBy('date', 'ASC')
        ->get();
        
        $new_d = [];
        foreach ($news3d as $key => $value) {
            if($value->comments_count == 0) {
                $new_d[] = $value;
            }
        }
        // dd($new_d);

        $new2w = Newc::withCount(['comments'])
        ->where('rss_new_id', '>', 0)
        ->whereHas('rssmews', function($qr){
            $qr->where('destroy', 1);
        })
        ->where('date', '<=', $weeks2)
        ->get();
        return view('news.list_cron_delete', compact(['new_d','new2w']));
    }

    public function listByCategory(Request $request){
        $news = Newc::select(['id', 'title']);
        if($request->category_id) {
            $news = $news->where('category_id', $request->category_id)->orWhere('category_id', 0);
        }
        if($request->category_new_id) {
            $news = $news->where('category_new_id', $request->category_new_id);
        }
        $news = $news->get();
        return json_encode($news);
    }

    public function get_banner($request)
    {
        $user_id = get_user_id($request);
        $push_user = User::where('id', $user_id)->first();
        $callback = function($query)use($user_id) {
            $query->where('user_id', $user_id);
        };
        $categories = Category::whereHas('user_category', $callback)->with(['user_category' => $callback])->where('publish', 0)->pluck('id')->toArray();
        $banners = NewBanner::select('id', 'image','video_id', 'type', 'url')
                    ->whereIn('category_id', $categories);
        $banners = $banners->where(function ($queryt) use($push_user){
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
        $banners = $banners->where('publish', 1)->get();
        $banner = [];
        if(count($banners)) {
            $banner = @$banners[rand(0,count($banners)-1)];
            $banner->qr = 1;
            if($banner->type==1) {
                $videos = SeminarController::get_video_by_video_id($user_id, $banner->video_id, $banner);
                $banner->video = $videos;
            }
            list($width, $height) = getimagesize('/var/www/html'.$banner->image);
            $banner->width = $width;
            $banner->height = $height;
            // $data_banner[] = $banner;  
        }
        return $banner;
    }
}
