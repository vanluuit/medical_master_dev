<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\NewDefault;
use Illuminate\Support\Facades\Storage;

class NewDefaultController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $new = NewDefault::first();
        return view('newdefault.index', compact(['new']));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        // $cates = Category::pluck('category','id')->toArray();
        // array_unshift($cates, '全ての学会');
        // $categories = CategoryNew::where('id', '>', 0)->pluck('category_name','id');
        // return view('newdefault.create', compact(['categories','cates']));
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
        // $data = $request->all();
        // unset($data['_token']);
        // $rs = RssNew::insertGetId($data);
        // if($rs) {
        //     return redirect(route('rssnews.index'));
        // }else{
        //     return back();
        // }
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
        $newdf = NewDefault::find($id);
        return view('newdefault.edit', compact(['newdf']));
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
        $rs = NewDefault::find($id)->update($data);
        if($rs) {
            if($request->delete){
                $news = NewDefault::find($id);
                $thumbnail_u = $news->thumbnail;
                $thumbnail_u = str_replace('/storage/app/', '', $thumbnail_u);
                $news->thumbnail = "";
                $news->save();
                Storage::delete($thumbnail_u);
            }else{
                if($request->thumbnail){
                    $news = NewDefault::find($id);
                    $thumbnail_u = $news->thumbnail;
                    $thumbnail_u = str_replace('/storage/app/', '', $thumbnail_u);
                    $path = '/storage/app/'.@$request->file('thumbnail')->store('news');
                    $news->thumbnail = $path;
                    $news->save();
                    Storage::delete($thumbnail_u);
                }
            }
            
            return redirect(route('newdefault.index'));
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
