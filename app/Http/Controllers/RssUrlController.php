<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\RssUrl;
use App\Category;

class RssUrlController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $rssurls = RssUrl::paginate(20);
        return view('rssurl.index', compact(['rssurls']));
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
        return view('rssurl.create', compact(['categories']));
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
        $rs = RssUrl::insertGetId($data);
        if($rs) {
            return redirect(route('rssurls.index'));
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
        $rssurl = RssUrl::find($id);
        $categories = Category::pluck('category','id');
        return view('rssurl.edit', compact(['rssurl','categories']));
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
        $rs = RssUrl::find($id)->update($data);
        if($rs) {
            return redirect(route('rssurls.index'));
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
        RssUrl::destroy($id);
        return redirect(route('rssurls.index'));
    }
}
