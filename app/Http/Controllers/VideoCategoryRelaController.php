<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Category;
use App\Channel;
use App\Video;
use App\VideoCategoryRela;

class VideoCategoryRelaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //
        if(!$request->category_id) return back();
        $category = Category::find($request->category_id);
        $categoryRelas=VideoCategoryRela::with(['association','videos'])->where('category_id', $request->category_id)->orderBy('nabi', 'ASC')->get();
        return view('categoryvideorelas.index', compact(['category','categoryRelas']));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        //
        $vd = VideoCategoryRela::where('category_id', $request->category_id)->pluck('video_id');
        // if(count($vd)>=4) return back();
        $category = Category::find($request->category_id);
        $channels = Channel::select('id','logo','title','discription','sponser','publish_date')
            ->whereHas('association', function($query)use($request) {
                $query->where('category_id', $request->category_id);
            })
            ->pluck('id');
        $vd = VideoCategoryRela::where('category_id', $request->category_id)->pluck('video_id');
        // dd($vd);
        $videos = Video::whereIn('channel_id', $channels)->whereNotIn('id', $vd)->pluck('title','id');

        return view('categoryvideorelas.create', compact(['category','videos']));
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
        $rs = VideoCategoryRela::insertGetId($data);
        return redirect(route('associations_video.index').'?category_id='.$request->category_id);
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
        $category = Category::find($request->category_id);
        $channels = Channel::select('id','logo','title','discription','sponser','publish_date')
            ->whereHas('association', function($query)use($request) {
                $query->where('category_id', $request->category_id);
            })
            ->pluck('id');
        $VideoCategoryRela = VideoCategoryRela::find($id);
        $vd = VideoCategoryRela::where('category_id', $request->category_id)->pluck('video_id');
        foreach ($vd as $key => $value) {
            // dd($value);
            if($value==$VideoCategoryRela->video_id) {
                unset($vd[$key]);
            }
        }
        // dd($vd);
        $videos = Video::whereIn('channel_id', $channels)->whereNotIn('id', $vd)->pluck('title','id');
       
        return view('categoryvideorelas.edit', compact(['category','videos','VideoCategoryRela']));
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
        $rs = VideoCategoryRela::find($id)->update($data);
        return redirect(route('associations_video.index').'?category_id='.$request->category_id);
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
        VideoCategoryRela::destroy($id);
        return redirect(route('associations_video.index').'?category_id='.$request->category_id);
    }

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
            VideoCategoryRela::where('id', $value)->update([
                'nabi' => $nabi
            ]);
            $nabi++;
        }
        echo '1';die();
    }
}
