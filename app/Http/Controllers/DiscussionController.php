<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use App\CategoryDiscussion;
use App\Category;
use App\User;
use DB;
use App\Discussion;
use App\CommentDiscussion;
use UploadedFile;
use SocketIO;
use Illuminate\Support\Facades\Storage;

class DiscussionController extends Controller
{
    public function api_authentication(Request $request)
    {
        $user_id = get_user_id($request);
        $push_user = User::where('id', $user_id)->first();

        $is_login = Discussion::find($request->id); 
        if($is_login && Crypt::decryptString($is_login->password) == $request->password) {
            if($push_user){
                if($push_user->login_discussion) {
                    $login_discussion_p = json_decode($push_user->login_discussion);
                }else{
                    $login_discussion_p = [];
                }
            }else{
                $login_discussion_p = [];
            }
            if (($key = array_search($request->id, $login_discussion_p)) == false) {
                $login_discussion_p[] = $request->id;
                $data_login_discussion = ['login_discussion'=>json_encode(array_merge($login_discussion_p))];
                User::where('id', $user_id)->update($data_login_discussion);
            }
            $data["statusCode"] = 0;
            $data["message"] = "";
            $data['data']['is_check'] = true;    
        }else{
            $data["statusCode"] = 1;
            $data["message"] = "パスワードが間違っています。";
            $data['data']['is_check'] = false; 
        }
        return response()->json($data);
    }

    public function api_top_by_association(Request $request)
    {
        $user_id = get_user_id($request);
        $push_user = User::where('id', $user_id)->first();
        if($push_user){
            if($push_user->login_discussion) {
                $login_discussion_p = json_decode($push_user->login_discussion);
            }else{
                $login_discussion_p = [];
            }
        }else{
            $login_discussion_p = [];
        }

        $callback = function($query)use($user_id) {
            $query->where('user_id', $user_id);
        };
       $categories = Category::whereHas('user_category', $callback)->with(['user_category' => $callback])->where('publish', 0)->pluck('id');
        // $rss = Rss::All();
        $Discussion = Discussion::select('id','title','url', 'discription', 'image1', 'image2', 'image3','status', 'new', 'created_at', 'updated_at')
        ->withCount(['is_own'=>function($qr1)use($user_id){
                    $qr1->where('user_id', $user_id);
                }])
        ->whereIn('category_id', $categories)
        ->where('category_id', $request->association_id)
        ->orderBy('updated_at', 'DESC')
        ->take(5)
        ->get();
        foreach ($Discussion as $key => $value) {
            $doted = '';
            if($key<=2) $value->new = 1;
            if(mb_strlen($value->title) > 50) $value->title = mb_substr($value->title, 0, 50).'...';
            if(in_array($value->id, $login_discussion_p) || $value->status == 0 || $value->is_own_count > 0) {
               $value->unlock = 1;
            }else{
                $value->unlock = 0;
            }
            $value->date_update = date('Y-m-d H:i', strtotime($value->updated_at));
        }
        if(count($Discussion)) {
            $data["statusCode"] = 0;
            $data["message"] = "";
            $data['data'] = $Discussion;  
        }else{
            $data["statusCode"] = 0;
            $data["message"] = "";
            $data['data'] = [];
        }
        
        return response()->json($data);
    }

    public function api_list_by_association(Request $request)
    {
        $user_id = get_user_id($request);
        $push_user = User::where('id', $user_id)->first();
        if($push_user){
            if($push_user->login_discussion) {
                $login_discussion_p = json_decode($push_user->login_discussion);
            }else{
                $login_discussion_p = [];
            }
        }else{
            $login_discussion_p = [];
        }
        $callback = function($query)use($user_id) {
            $query->where('user_id', $user_id);
        };
       $categories = Category::whereHas('user_category', $callback)->with(['user_category' => $callback])->where('publish', 0)->pluck('id');
        // $rss = Rss::All();
        $Discussion = Discussion::select('id','title','url', 'discription', 'image1', 'image2', 'image3','status', 'new', 'created_at', 'updated_at')
        ->withCount(['is_own'=>function($qr1)use($user_id){
                    $qr1->where('user_id', $user_id);
                },'is_mypage'=>function($qr1)use($user_id){
                $qr1->where('user_id', $user_id);
            }, 'is_like'=>function($qr2)use($user_id){
                    $qr2->where('user_id', $user_id);
                },'count_like'])
        ->whereIn('category_id', $categories)
        ->where('category_id', $request->association_id)
        ->orderBy('updated_at', 'DESC');
        if(isset($request->page)){
           $Discussion = $Discussion->paginate(10);
        }else{
            $Discussion = $Discussion->take(10)->get();
        }
        // ->take(10)
        
        
        if(count($Discussion)) {
            foreach ($Discussion as $key => $value) {
                if($key <=2) $value->new = 1;
                if(in_array($value->id, $login_discussion_p) || $value->status == 0 || $value->is_own_count > 0) {
                   $value->unlock = 1;
                }else{
                    $value->unlock = 0;
                }
                $value->date_update = date('Y-m-d H:i', strtotime($value->updated_at));
            }
            $data["statusCode"] = 0;
            $data["message"] = "";
            $data['data'] = $Discussion;  
        }else{
            $data["statusCode"] = 0;
            $data["message"] = "";
            $data['data'] = [];
        }
        
        return response()->json($data);
    }

    public function api_detail(Request $request)
    {
        $user_id = get_user_id($request);
        $push_user = User::where('id', $user_id)->first();
        if($push_user){
            if($push_user->login_discussion) {
                $login_discussion_p = json_decode($push_user->login_discussion);
            }else{
                $login_discussion_p = [];
            }
        }else{
            $login_discussion_p = [];
        }
        $callback = function($query)use($user_id) {
            $query->where('user_id', $user_id);
        };
        $categories = Category::whereHas('user_category', $callback)->with(['user_category' => $callback])->where('publish', 0)->pluck('id');
        // $rss = Rss::All();
        // return response()->json($categories);
        $Discussion = Discussion::select('id','title','url','user_id', 'discription', 'image1', 'image2', 'image3','status', 'password', 'created_at', 'updated_at')
        ->withCount(['is_mypage'=>function($qr1)use($user_id){
                $qr1->where('user_id', $user_id);
            }, 'is_own'=>function($qr2)use($user_id){
                $qr2->where('user_id', $user_id);
            }, 'is_like'=>function($qr2)use($user_id){
                $qr2->where('user_id', $user_id);
            }, 'count_like'])
        ->whereIn('category_id', $categories)
        ->where('id', $request->id)
        ->with(['users','comments'=>function($qr)use($user_id){
            $qr->withCount(['is_own'=>function($qr1)use($user_id){
                    $qr1->where('user_id', $user_id);
                }, 'is_like'=>function($qr1)use($user_id){
                    $qr1->where('user_id', $user_id);
                }, 'count_like']);
        },'comments.parent'=>function($qr)use($user_id){
            $qr->withCount(['is_own'=>function($qr1)use($user_id){
                    $qr1->where('user_id', $user_id);
                }, 'is_like'=>function($qr1)use($user_id){
                    $qr1->where('user_id', $user_id);
                }]);
        },'comments.parent.users','comments.users'])
        ->first();
        if($Discussion->status == 1){
            if($Discussion->password != "") {
                $Discussion->password = Crypt::decryptString($Discussion->password);
            }
        }else{
            $Discussion->password = '';
        }
        if(in_array($Discussion->id, $login_discussion_p) || $Discussion->status == 0 || $Discussion->is_own_count > 0) {
           $Discussion->unlock = 1;
        }else{
            $Discussion->unlock = 0;
        }
        // $Discussion->updated_at = date('Y-m-d H:i', strtotime($Discussion->updated_at));
        $Discussion->date_update = date('Y-m-d H:i', strtotime($Discussion->updated_at));
        if($Discussion) {
            $data["statusCode"] = 0;
            $data["message"] = "";
            $data['data'] = $Discussion;  
        }else{
            $data["statusCode"] = 0;
            $data["message"] = "";
            $data['data'] = $Discussion;
        }
        
        return response()->json($data);
    }


    public function api_add(Request $request)
    {
        $user_id = get_user_id($request);
        $callback = function($query)use($user_id) {
            $query->where('user_id', $user_id);
        };
        $categories = Category::whereHas('user_category', $callback)->with(['user_category' => $callback])->where('publish', 0)->pluck('id');
        if(!in_array($request->association_id, $categories->toArray())){
            $data["statusCode"] = 1;
            $data["message"] = "User is not in association";
            $data['data'] = [];
        }else{
            $discussion = new Discussion;
            $discussion->category_id = @$request->association_id;
            // $discussion->category_discussion_id = $request->category_id;
            $discussion->user_id = $user_id;
            $discussion->title = @$request->title;
            $discussion->url = @$request->url;
            $discussion->discription = @$request->discription;
            $discussion->status = @$request->status;
            if($request->status==1) {
                $discussion->password = Crypt::encryptString($request->password);
            }
            $discussion->save();
            $last_id = $discussion->id;

            // $file = $request->file('image1')->path();
            $path1=$path2=$path3='';
            if($request->image1){
                $path1 = '/storage/app/'.@$request->file('image1')->store('discussion');
            }
            if($request->image2){
                $path2 = '/storage/app/'.@$request->file('image2')->store('discussion');
            }
            if($request->image3){
                $path3 = '/storage/app/'.@$request->file('image3')->store('discussion');
            }
            $discussion->update([
                'image1' => $path1,
                'image2' => $path2,
                'image3' => $path3,
            ]);
            // Storage::delete('image1\t9KzDypN5LAoyrdonEDlk0dkcWaXqzIliWuDHtEx.jpeg');

            $data["statusCode"] = 0;
            $data["message"] = "";
            $data['data'] = $discussion;
        }
        return response()->json($data);
    }

    public function api_edit(Request $request)
    {
        $user_id = get_user_id($request);
        $check = Discussion::where('id', $request->id)->where('user_id', $user_id);
        // return response()->json($check->first());
        if($check->first()){
            $discription = $check->first();

            $data_ar = [];
            $data_ar['title'] = @$request->title;
            $data_ar['url'] = @$request->url;
            // $data_ar['category_discussion_id'] = @$request->category_id;
            $data_ar['discription'] = @$request->discription;
            $data_ar['status'] = @$request->status;
            $password = $check->first()->password;
            if($password != '') {
                $password = Crypt::decryptString($password);
            }
            // return response()->json($password);
            // $password = 'vanluu';
            if(@$request->status == 1) {
            	if($password != @$request->password) {
	            	$user_ar = User::where('login_discussion', 'LIKE', '%['.$request->id.',%')
	            	->orWhere('login_discussion', 'LIKE', '%,'.$request->id.',%')
	            	->orWhere('login_discussion', 'LIKE', '%,'.$request->id.']%')
	            	->orWhere('login_discussion', 'LIKE', '%"'.$request->id.'"%')
	            	->get();
	                if(count($user_ar)) {
	                    foreach ($user_ar as $key_u => $value_u) {
	                        if($value_u->login_discussion) {
	                            $login_discussion_p = json_decode($value_u->login_discussion);
	                        }else{
	                            $login_discussion_p = [];
	                        }
	                        if (($key = array_search($request->id, $login_discussion_p)) !== false) {
	                            unset($login_discussion_p[$key]);
	                            $data_login_discussion = ['login_discussion'=>json_encode(array_merge($login_discussion_p))];
	                            User::where('id', $user_id)->update($data_login_discussion);
	                        }
	                    }
	                }
	                $data_ar['password'] = Crypt::encryptString(@$request->password);
                }
            }else{
                $data_ar['password'] = '';
            }

            if(@$request->action1){
                if(@$request->action1=="delete") {
                    $media_u = $discription->image1;
                    $media_u = str_replace('/storage/app/', '', $media_u);
                    Storage::delete($media_u);
                    $data_ar['image1'] = "";
                }
                if(@$request->action1=="replace") {
                    $media_u = $discription->image1;
                    $media_u = str_replace('/storage/app/', '', $media_u);
                    Storage::delete($media_u);
                    $data_ar['image1'] = '/storage/app/'.$request->file('image1')->store('discussion');
                }
            }
            if(@$request->action2){
                if(@$request->action2=="delete") {
                    $media_u = $discription->image2;
                    $media_u = str_replace('/storage/app/', '', $media_u);
                    Storage::delete($media_u);
                    $data_ar['image2'] = "";
                }
                if(@$request->action2=="replace") {
                    $media_u = $discription->image2;
                    $media_u = str_replace('/storage/app/', '', $media_u);
                    Storage::delete($media_u);
                    $data_ar['image2'] = '/storage/app/'.$request->file('image2')->store('discussion');
                }
            }
            if(@$request->action3){
                if(@$request->action3=="delete") {
                    $media_u = $discription->image3;
                    $media_u = str_replace('/storage/app/', '', $media_u);
                    Storage::delete($media_u);
                    $data_ar['image3'] = "";
                }
                if(@$request->action3=="replace") {
                    $media_u = $discription->image3;
                    $media_u = str_replace('/storage/app/', '', $media_u);
                    Storage::delete($media_u);
                    $data_ar['image3'] = '/storage/app/'.$request->file('image3')->store('discussion');
                }
            }
            $check->update($data_ar);
            $data["statusCode"] = 0;
            $data["message"] = "";
        }else{
            $data["statusCode"] = 1;
            $data["message"] = "error";
            $data['data'] = $check->first();
        }
        return response()->json($data);
    }
    
    public function api_delete(Request $request)
    {
        //
        $user_id = get_user_id($request);
        $check = Discussion::where('id', $request->id)->where('user_id', $user_id);
        if($check->first()) {
            $comments = CommentDiscussion::where('discussion_id', $request->id)->get();
            if(count( $comments)) {
                foreach ($comments as $key => $comment) {
                    if($comment->image1) {
                        $media_u = $comment->image1;
                        $media_u = str_replace('/storage/app/', '', $media_u);
                        Storage::delete($media_u);
                    }
                    if($comment->image2) {
                        $media_u = $comment->image2;
                        $media_u = str_replace('/storage/app/', '', $media_u);
                        Storage::delete($media_u);
                    }
                    if($comment->image3) {
                        $media_u = $comment->image3;
                        $media_u = str_replace('/storage/app/', '', $media_u);
                        Storage::delete($media_u);
                    }
                }
            }
            $discussion = Discussion::find($request->id);
            if($discussion) {
                if($discussion->image1) {
                    $media_u = $discussion->image1;
                    $media_u = str_replace('/storage/app/', '', $media_u);
                    Storage::delete($media_u);
                }
                if($discussion->image2) {
                    $media_u = $discussion->image2;
                    $media_u = str_replace('/storage/app/', '', $media_u);
                    Storage::delete($media_u);
                }
                if($discussion->image3) {
                    $media_u = $discussion->image3;
                    $media_u = str_replace('/storage/app/', '', $media_u);
                    Storage::delete($media_u);
                }
            }
            
            DB::table('user_discussions')->where('discussion_id', $request->id)->delete();
            DB::table('like_discussions')->where('discussion_id', $request->id)->delete();
            DB::table('comment_discussions')->where('discussion_id', $request->id)->delete();
            Discussion::destroy($request->id);
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
        $check = DB::table('like_discussions')->where('discussion_id', $request->id)->where('user_id', $user_id)->first();
        if($check) {
            $rs = DB::table('like_discussions')->where('discussion_id', $request->id)->where('user_id', $user_id)->delete();
        }else{
            $rs = DB::table('like_discussions')->insert([
                'discussion_id' => $request->id,
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
    public function api_like_comment_set(Request $request)
    {
        $user_id = get_user_id($request);
        $check = DB::table('like_comment_discusions')->where('comment_id', $request->id)->where('user_id', $user_id)->first();
        if($check) {
            $rs = DB::table('like_comment_discusions')->where('comment_id', $request->id)->where('user_id', $user_id)->delete();
        }else{
            $rs = DB::table('like_comment_discusions')->insert([
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


    public function api_add_comment(Request $request)
    {
        $user_id = get_user_id($request);
        $callback = function($query)use($user_id) {
            $query->where('user_id', $user_id);
        };
        $categories = Category::whereHas('user_category', $callback)->with(['user_category' => $callback])->where('publish', 0)->pluck('id');
        $Discussion = Discussion::select('id','title', 'discription', 'image1', 'image2', 'image3','created_at')
        ->whereIn('category_id', $categories)
        ->where('id', $request->discussion_id)
        ->first();
        // return response()->json($request);
        if($Discussion){
            $comment = new CommentDiscussion;
            $comment->discussion_id = @$request->discussion_id;
            $comment->user_id = $user_id;
            $comment->push = 1;
            $comment->comment = @$request->comment;
            if($request->parent_id) {
                $comment->parent_id = @$request->parent_id;
            }
            // $comment->save();
            $path1=$path2=$path3='';
            if($request->image1){
                $comment->image1 = '/storage/app/'.@$request->file('image1')->store('discussion');
            }else{
                $comment->image1 = '';
            }
            if($request->image2){
                $comment->image2 = '/storage/app/'.@$request->file('image2')->store('discussion');
            }else{
                $comment->image2 = '';
            }
            if($request->image3){
                $comment->image3 = '/storage/app/'.@$request->file('image3')->store('discussion');
            }else{
                $comment->image3 = '';
            }
            if($request->cm_file){
                $comment->cm_file = '/storage/app/'.@$request->file('cm_file')->storeAs('discussion', $request->file('cm_file')->getClientOriginalName());
            }else{
                $comment->cm_file = '';
            }
            $comment->save();
            // response()->json($comment);
            Discussion::where('id', $request->discussion_id)->update(['date_ud'=>date('Y-m-d H:i:s')]);
            $data["statusCode"] = 0;
            $data["message"] = "";
            $data["data"] = $comment;
        }else{
            $data["statusCode"] = 1;
            $data["message"] = "User is not in association";
        }
        return response()->json($data);
    }

    public function api_edit_comment(Request $request)
    {

        $user_id = get_user_id($request);
        $check = CommentDiscussion::where('id', $request->id)->where('user_id', $user_id);
        if($check->first()){
            $comment = $check->first();
            $data_ar = [];
            $data_ar['comment'] = @$request->comment;
            if(@$request->action1){
                if(@$request->action1=="delete") {
                    $media_u = $comment->image1;
                    $media_u = str_replace('/storage/app/', '', $media_u);
                    Storage::delete($media_u);
                    $data_ar['image1'] = "";
                }
                if(@$request->action1=="replace") {
                    $media_u = $comment->image1;
                    $media_u = str_replace('/storage/app/', '', $media_u);
                    Storage::delete($media_u);
                    $data_ar['image1'] = '/storage/app/'.$request->file('image1')->store('discussion/comment');
                }
            }
            if(@$request->action2){
                if(@$request->action2=="delete") {
                    $media_u = $comment->image2;
                    $media_u = str_replace('/storage/app/', '', $media_u);
                    Storage::delete($media_u);
                    $data_ar['image2'] = "";
                }
                if(@$request->action2=="replace") {
                    $media_u = $comment->image2;
                    $media_u = str_replace('/storage/app/', '', $media_u);
                    Storage::delete($media_u);
                    $data_ar['image2'] = '/storage/app/'.$request->file('image2')->store('discussion/comment');
                }
            }
            if(@$request->action3){
                if(@$request->action3=="delete") {
                    $media_u = $comment->image3;
                    $media_u = str_replace('/storage/app/', '', $media_u);
                    Storage::delete($media_u);
                    $data_ar['image3'] = "";
                }
                if(@$request->action3=="replace") {
                    $media_u = $comment->image3;
                    $media_u = str_replace('/storage/app/', '', $media_u);
                    Storage::delete($media_u);
                    $data_ar['image3'] = '/storage/app/'.$request->file('image3')->store('discussion/comment');
                }
            }
            if(@$request->action_file){
                if(@$request->action_file=="delete") {
                    $media_u = $comment->cm_file;
                    $media_u = str_replace('/storage/app/', '', $media_u);
                    Storage::delete($media_u);
                    $data_ar['cm_file'] = "";
                }
                if(@$request->action_file=="replace") {
                    $media_u = $comment->cm_file;
                    $media_u = str_replace('/storage/app/', '', $media_u);
                    Storage::delete($media_u);
                    $data_ar['cm_file'] = '/storage/app/'.@$request->file('cm_file')->storeAs('discussion', $request->file('cm_file')->getClientOriginalName());
                }
            }
            $check->update($data_ar);
            $data_rs = CommentDiscussion::where('id', $request->id)->where('user_id', $user_id)->first();
            $data["statusCode"] = 0;
            $data["message"] = "";
            $data["data"] = $data_rs;
        }else{
            $data["statusCode"] = 1;
            $data["message"] = "error";
        }
        return response()->json($data);
    }

    public function api_delete_comment(Request $request)
    {
        $user_id = get_user_id($request);
        $check = CommentDiscussion::where('id', $request->id)->where('user_id', $user_id);
        if($check->first()){
            $comment = $check->first();
            if($comment->image1) {
                $media_u = $comment->image1;
                $media_u = str_replace('/storage/app/', '', $media_u);
                Storage::delete($media_u);
            }
            if($comment->image2) {
                $media_u = $comment->image2;
                $media_u = str_replace('/storage/app/', '', $media_u);
                Storage::delete($media_u);
            }
            if($comment->image3) {
                $media_u = $comment->image3;
                $media_u = str_replace('/storage/app/', '', $media_u);
                Storage::delete($media_u);
            }
            CommentDiscussion::destroy($request->id);
            $data["statusCode"] = 0;
            $data["message"] = ""; 
        }else{
            $data["statusCode"] = 1;
            $data["message"] = "error";
        }
        return response()->json($data);
    }

    public function api_add_report_comment(Request $request)
    {
        $user_id = get_user_id($request);
        $check = DB::table('comment_discussion_report')->where('comment_id', $request->comment_id)->where('user_id', $user_id)->first();
        if($check) {
            $rs = DB::table('comment_discussion_report')->where('comment_id', $request->comment_id)->where('user_id', $user_id)->delete();
        }else{
            $rs = DB::table('comment_discussion_report')->insert([
                'comment_id' => $request->comment_id,
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
        $check = DB::table('user_discussions')->where('discussion_id', $request->id)->where('user_id', $user_id)->first();
        if($check) {
            $rs = DB::table('user_discussions')->where('discussion_id', $request->id)->where('user_id', $user_id)->delete();
        }else{
            $rs = DB::table('user_discussions')->insert([
                'discussion_id' => $request->id,
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
        $callback = function($query)use($user_id) {
            $query->where('user_id', $user_id);
        };
        $categories = Category::whereHas('user_category', $callback)->with(['user_category' => $callback])->where('publish', 0)->pluck('id');
        // $rss = Rss::All();
        $Discussion = Discussion::select('id','title','url', 'discription', 'image1', 'image2', 'image3','status', 'new', 'created_at', 'updated_at')
        ->whereIn('category_id', $categories)
        ->where('category_id', $request->association_id)
        // ->where('category_discussion_id', $request->category_id)
        ->whereHas('is_mypage', function($qrh)use($user_id){
            $qrh->where('user_id', $user_id);
        })
        ->take(30)
        ->get();
        if(count($Discussion)) {
            foreach ($Discussion as $keyr => $valuer) {
                if(in_array($valuer->id, $discussion_p)) {
                   $valuer->unread = 1;
                }else{
                    $valuer->unread = 0;
                }
                $valuer->date_update = date('Y-m-d H:i', strtotime($valuer->updated_at));
            }
            $data["statusCode"] = 0;
            $data["message"] = "";
            $data['data'] = $Discussion;  
        }else{
            $data["statusCode"] = 0;
            $data["message"] = "";
            $data['data'] = $Discussion;
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
        $categories = Category::pluck('category','id');
        $discussions = Discussion::where('id','>',0);
        if($request->category_id){
            $discussions = $discussions->where('category_id', $request->category_id);
        }
        $discussions = $discussions->orderBy('id', 'DESC')->paginate(20);
        return view('discussion.index', compact(['discussions','categories']));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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
        $cate = CategoryDiscussion::pluck('category_name','id');
        $discussion = Discussion::find($id);
        return view('discussion.edit', compact(['categories','cate','discussion']));
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
        unset($data['image1']);
        unset($data['image2']);
        unset($data['image3']);
        $rs = Discussion::find($id)->update($data);
        if($rs) {
            if($request->image1){
                $discussion = Discussion::find($id);
                $image1_u = $discussion->image1;
                $image1_u = str_replace('/storage/app/', '', $image1_u);
                $path = '/storage/app/'.@$request->file('image1')->store('discussion');
                $discussion->image1 = $path;
                $discussion->save();
                Storage::delete($image1_u);
            }
            if($request->image2){
                $discussion = Discussion::find($id);
                $image2_u = $discussion->image2;
                $image2_u = str_replace('/storage/app/', '', $image2_u);
                $path = '/storage/app/'.@$request->file('image2')->store('discussion');
                $discussion->image2 = $path;
                $discussion->save();
                Storage::delete($image2_u);
            }
            if($request->image3){
                $discussion = Discussion::find($id);
                $image3_u = $discussion->image3;
                $image3_u = str_replace('/storage/app/', '', $image3_u);
                $path = '/storage/app/'.@$request->file('image3')->store('discussion');
                $discussion->image3 = $path;
                $discussion->save();
                Storage::delete($image3_u);
            }
            return redirect(route('discussion.index'));
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
        $comments = CommentDiscussion::where('discussion_id', $id)->get();
        if(count( $comments)) {
            foreach ($comments as $key => $comment) {
                if($comment->image1) {
                    $media_u = $comment->image1;
                    $media_u = str_replace('/storage/app/', '', $media_u);
                    Storage::delete($media_u);
                }
                if($comment->image2) {
                    $media_u = $comment->image2;
                    $media_u = str_replace('/storage/app/', '', $media_u);
                    Storage::delete($media_u);
                }
                if($comment->image3) {
                    $media_u = $comment->image3;
                    $media_u = str_replace('/storage/app/', '', $media_u);
                    Storage::delete($media_u);
                }
            }
        }
        $discussion = Discussion::find($id);
        if($discussion) {
            if($discussion->image1) {
                $media_u = $discussion->image1;
                $media_u = str_replace('/storage/app/', '', $media_u);
                Storage::delete($media_u);
            }
            if($discussion->image2) {
                $media_u = $discussion->image2;
                $media_u = str_replace('/storage/app/', '', $media_u);
                Storage::delete($media_u);
            }
            if($discussion->image3) {
                $media_u = $discussion->image3;
                $media_u = str_replace('/storage/app/', '', $media_u);
                Storage::delete($media_u);
            }
        }
        
        DB::table('user_discussions')->where('discussion_id', $id)->delete();
        DB::table('like_discussions')->where('discussion_id', $id)->delete();
        DB::table('comment_discussions')->where('discussion_id', $id)->delete();
        Discussion::destroy($id);
        return redirect(route('discussion.index'));
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function comments_list(Request $request)
    {
        //
        $discussions = Discussion::pluck('title','id');
        $comments = CommentDiscussion::withCount(['count_report']);
        if($request->discussion_id){
            $comments = $comments->where('discussion_id', $request->discussion_id);
        }
        if($request->report){
            $comments = $comments->whereHas('count_report');
        }
        if($request->id){
            $comments = $comments->where('id', $request->id);
        }
        $comments = $comments->orderBy('id', 'DESC')->paginate(20);
        return view('discussion.comments', compact(['comments','discussions','dis']));
    }

    public function comments_empty($id, Request $request)
    {
        //
        $comment = CommentDiscussionReport::where('comment_id', $id)->delete();
        
        return redirect(route('discussion.comments_list').'?discussion_id='.$request->discussion_id);
    }

    public function comments_destroy($id, Request $request)
    {
        //
        $comment = CommentDiscussion::find($id);
        if($comment) {
            if($comment->image1) {
                $media_u = $comment->image1;
                $media_u = str_replace('/storage/app/', '', $media_u);
                Storage::delete($media_u);
            }
            if($comment->image2) {
                $media_u = $comment->image2;
                $media_u = str_replace('/storage/app/', '', $media_u);
                Storage::delete($media_u);
            }
            if($comment->image3) {
                $media_u = $comment->image3;
                $media_u = str_replace('/storage/app/', '', $media_u);
                Storage::delete($media_u);
            }
        }
        CommentDiscussion::destroy($id);
        return redirect(route('discussion.comments_list').'?discussion_id='.$request->discussion_id);
    }
}
