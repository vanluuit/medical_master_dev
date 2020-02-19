<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Pr;
use App\Channel;
use App\Video;
use App\Category;
use App\User;
use App\UserPush;

class PrController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //
        $prs = Pr::with(['video','category'])->where('id', '>', 0);
        if($request->category_id) {
            $prs = $prs->where('category_id', $request->category_id);
        }
        $prs = $prs->orderBy('id', 'DESC')->paginate(20);
        $categories = Category::pluck('category','id');
        return view('prs.index', compact(['prs', 'categories']));
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
        $new = Pr::find($request->id);
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
        $categories = Category::pluck('category','id');
        return view('prs.create', compact(['categories']));
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
        $rs = Pr::insertGetId($data);
        if($rs) {
            if($request->image){
                $path = '/storage/app/'.@$request->file('image')->store('prs');
                $prsss = Pr::find($rs);
                $prsss->image = $path;
                $prsss->save();
            }
            // $title = '学会からのお知らせが更新されました';
            // $push_data = [
            //     'title' => $title,
            //     'body' => $title,
            //     'type' => 1,
            //     'association_id' => $request->category_id
            // ];
            // $users = User::with(['devices'])
            // ->whereHas('association', function($qr)use($data){
            //     $qr->where('category_id', $data['category_id']);
            // })
            // ->has('devices')
            // ->get();
            // if(count($users)) {
            //     foreach ($users as $key => $value) {
            //         $push_user = UserPush::where('user_id', $value->id)->first();
            //         if($push_user){
            //             if($push_user->pr) {
            //                 $pr_p = json_decode($push_user->pr);
            //             }else{
            //                 $pr_p = [];
            //             }
            //         }else{
            //             $pr_p = [];
            //         }
            //         $pr_p[] = $rs;
            //         $ck = ['user_id'=>$value->id];
            //         $datapr = ['pr'=>json_encode($pr_p)];
            //         UserPush::updateOrCreate($ck, $datapr);
            //         foreach ($value->devices as $keyd => $valued) {
            //             notification_push($valued->token, $title, $push_data);
            //         }
            //     }
            // }
            return redirect(route('prs.index'));
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
        $categories = Category::pluck('category','id');
        $pr = Pr::with(['video'])->where('id', $id)->first();

        $channels = Channel::select('id','logo','title','discription','sponser','publish_date')
            ->whereHas('association', function($query)use($pr) {
                $query->where('category_id', $pr->category_id);
            })
            // ->whereDate('publish_date','<',date('Y-m-d H:i:s'))
            // ->where('category_channel_id',@$request->category_id)
            ->pluck('id');
            // 

        $videos = Video::whereIn('channel_id', $channels)->pluck('title', 'id');
// dd($videos);
        return view('prs.edit', compact(['categories','pr','videos']));
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
        $rs = Pr::find($id)->update($data);
        if($rs) {
            if($request->image){
                $prs = Pr::find($id);
                $image_u = $prs->media;
                $image_u = str_replace('/storage/app/', '', $image_u);
                $path = '/storage/app/'.@$request->file('image')->store('prs');
                $prs->image = $path;
                $prs->save();
                Storage::delete($image_u);
            }
            return redirect(route('prs.index'));
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
        Pr::destroy($id);
        return redirect(route('prs.index'));
    }
}
