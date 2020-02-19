<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\CategoryNew;
use App\Newc;
use App\UserNew;
use App\CommentNew;
use DB;
use Illuminate\Support\Facades\Storage;

class CategoryNewController extends Controller
{
    // api
    
    public function api_list()
    {
        $categories = CategoryNew::select(['id','category_name'])
                            ->where('publish', 1)
                            ->orderBy('nabi', 'ASC')
                            ->get(); 
        // $tt = count($categories);
        // $vt = floor($tt/2);
        // $td = $categories[0];
        // $categories[0] = $categories[ $vt ];
        // $categories[ $vt ] = $td;
        
        if($categories) {
            $data["statusCode"] = 0;
            $data["message"] = "";
            $data['data'] = $categories;  
        }else{
            $data["statusCode"] = 0;
            $data["message"] = "";
            $data['data'] = $categories;
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
        $Category = CategoryNew::where('id', 0)->first();

        $CategoryNews = CategoryNew::where('id', '>', 0)->orderBy('nabi', 'ASC')->paginate(2000);
        
        return view('categorynews.index',compact(['CategoryNews', 'Category']));
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
        $soft = $request->soft;
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
            CategoryNew::where('id', $value)->update([
                'nabi' => $nabi
            ]);
            $nabi++;
        }
        echo '1';die();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        return view('categorynews.create');
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
        $rs = CategoryNew::insertGetId($data);
        if($rs) {
            return redirect(route('category_news.index'));
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
        $CategoryNew = CategoryNew::find($id);
        return view('categorynews.edit', compact(['CategoryNew']));
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
        $rs = CategoryNew::find($id)->update($data);
        if($rs) {
            return redirect(route('category_news.index'));
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
        $news = Newc::with(['comments'])->where('category_new_id', $id)->get();
        if(count($news)) {
            foreach ($news as $key => $value) {
                if(@$value->media) {
                    $media = $value->media;
                    $media = str_replace('/storage/app/', '', $media);
                    Storage::delete($media);
                }
                CommentNew::destroy($value->id);
                if(@$value->comments) {
                    foreach (@$value->comments as $key_cm => $value_cm) {
                        DB::table('like_comments')->where('comment_id',$value_cm->id)->delete();
                    }
                }
                UserNew::where('new_id',$value->id)->delete();
                DB::table('like_news')->where('new_id',$value->id)->delete();
            }
            Newc::where('category_new_id',$id)->delete();
        }
        if($id > 0) {
            CategoryNew::destroy($id);
        }
        
        return redirect(route('category_news.index'));
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
        $new = CategoryNew::find($request->id);
        $new->publish = $request->publish;
        // $new->date = date('Y-m-d H:i:s');
        $new->save();
        echo $new->publish; die();
    }
}
