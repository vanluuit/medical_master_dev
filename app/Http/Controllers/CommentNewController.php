<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Newc;
use App\CommentNew;
use DB;

class CommentNewController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //
        if(!$request->new_id) return back();
        $News = Newc::where('id', $request->new_id)->first();
        $comments = CommentNew::where('new_id', $request->new_id);
        if($request->start_date) {
            $comments = $comments->where('created_at', '>=', $request->start_date);
        }
        if($request->end_date) {
            $comments = $comments->where('created_at', '>=', $request->end_date);
        }
        $comments = $comments->paginate(20);
        
        $id =  $request->new_id;
        return view('commentnews.index',compact(['comments','News','id']));
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
    public function edit($id, Request $request)
    {
        //
        if(!$request->new_id) return back();
        $comments = CommentNew::find($id);
        $new_id = $request->new_id;
        return view('commentnews.edit',compact(['comments','new_id']));
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
        if(!$request->new_id) return back();
        CommentNew::destroy($id);
        DB::table('like_comments')->where('comment_id',$id)->delete();
        return redirect(route('commentnews.index').'?new_id='. $request->new_id);
    }
}
