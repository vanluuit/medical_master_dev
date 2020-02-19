<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\RssNew;
use App\CategoryNew;
use App\Category;
use Illuminate\Support\Facades\Storage;

class RssNewController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $rssnews = RssNew::paginate(20);
        return view('rssnew.index', compact(['rssnews']));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        $cates = Category::pluck('category','id')->toArray();
        array_unshift($cates, '全ての学会');
        $categories = CategoryNew::where('id', '>', 0)->pluck('category_name','id');
        return view('rssnew.create', compact(['categories','cates']));
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
        unset($data['thumbnail']);
        $rs = RssNew::insertGetId($data);
        if($rs) {
            if($request->thumbnail){
                $news = RssNew::find($rs);
                $path = '/storage/app/'.@$request->file('thumbnail')->store('news');
                $news->thumbnail = $path;
                $news->save();
            }
            return redirect(route('rssnews.index'));
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
        $cates = Category::pluck('category','id')->toArray();
        array_unshift($cates, '全ての学会');
        $categories = CategoryNew::where('id', '>', 0)->pluck('category_name','id');
        $rssnew = RssNew::find($id);
        return view('rssnew.edit', compact(['rssnew','categories','cates']));
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
        unset($data['thumbnail']);
        unset($data['delete']);
        $rs = RssNew::find($id)->update($data);
        if($rs) {
            if($request->delete){
                $news = RssNew::find($id);
                $thumbnail_u = $news->thumbnail;
                $thumbnail_u = str_replace('/storage/app/', '', $thumbnail_u);
                $news->thumbnail = "";
                $news->save();
                // Storage::delete($thumbnail_u);
            }else{
                if($request->thumbnail){
                    $news = RssNew::find($id);
                    $thumbnail_u = $news->thumbnail;
                    $thumbnail_u = str_replace('/storage/app/', '', $thumbnail_u);
                    $path = '/storage/app/'.@$request->file('thumbnail')->store('news');
                    $news->thumbnail = $path;
                    $news->save();
                    // Storage::delete($thumbnail_u);
                }
            }
            return redirect(route('rssnews.index'));
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
        RssNew::destroy($id);
        return redirect(route('rssnews.index'));
    }
}
