<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Banner;
use App\Channel;
use App\Video;
use App\Category;
use App\Setting;
use Illuminate\Support\Facades\Storage;

class BannerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //
        $banners = Banner::with(['video','category'])->where('id', '>', 0);
        if($request->category_id) {
            $banners = $banners->where('category_id', $request->category_id);
        }
        $banners = $banners->orderBy('id', 'DESC')->paginate(20);
        $categories = Category::pluck('category','id');
        return view('banners.index', compact(['banners', 'categories']));
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
        $new = Banner::find($request->id);
        $new->publish = $request->publish;
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
        $banner_number = Setting::where('key_s','banner_number')->first();
        if($banner_number) {
            $number = $banner_number->value_s;
        }else{
            $number = 0;
        }   
        $location_ar = [];
        if($number > 0) {
            for ($i=1; $i <= $number ; $i++) { 
                $location_ar[$i] = $i;
            }
        }
        $categories = Category::pluck('category','id');
        return view('banners.create', compact(['categories','location_ar']));
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
        unset($data['image']);
        $rs = Banner::insertGetId($data);
        if($rs) {
            if($request->image){
                $path = '/storage/app/'.@$request->file('image')->store('banners');
                $bannersss = Banner::find($rs);
                $bannersss->image = $path;
                $bannersss->save();
            }
            return redirect(route('banners.index'));
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
        $banner_number = Setting::where('key_s','banner_number')->first();
        if($banner_number) {
            $number = $banner_number->value_s;
        }else{
            $number = 0;
        }   
        $location_ar = [];
        if($number > 0) {
            for ($i=1; $i <= $number ; $i++) { 
                $location_ar[$i] = $i;
            }
        }

        $categories = Category::pluck('category','id');
        $banner = Banner::with(['video'])->where('id', $id)->first();

        $channels = Channel::select('id','logo','title','discription','sponser','publish_date')
            ->whereHas('association', function($query)use($banner) {
                $query->where('category_id', $banner->category_id);
            })
            // ->whereDate('publish_date','<',date('Y-m-d H:i:s'))
            // ->where('category_channel_id',@$request->category_id)
            ->pluck('id');
            // 

        $videos = Video::whereIn('channel_id', $channels)->pluck('title', 'id');
// dd($videos);
        return view('banners.edit', compact(['categories','banner','videos','location_ar']));
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
        unset($data['image']);
        $rs = Banner::find($id)->update($data);
        if($rs) {
            if($request->image){
                $banners = Banner::find($id);
                $image_u = $banners->media;
                $image_u = str_replace('/storage/app/', '', $image_u);
                $path = '/storage/app/'.@$request->file('image')->store('banners');
                $banners->image = $path;
                $banners->save();
                Storage::delete($image_u);
            }
            return redirect(route('banners.index'));
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
        Banner::destroy($id);
        return redirect(route('banners.index'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function setNumber()
    {
        //
        $banner_number = Setting::where('key_s','banner_number')->first();
        if($banner_number) {
            $number = $banner_number->value_s;
        }else{
            $number = 0;
        }   
        return view('banners.setnumber', compact(['number']));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function postNumber(Request $request)
    {
        //
        $data = $request->all();
        unset($data['_token']);
        $ck = [
            'key_s' => 'banner_number'
        ];
        $rs = Setting::updateOrCreate($ck, $data);
        return redirect(route('banners.index'));
    }
}
