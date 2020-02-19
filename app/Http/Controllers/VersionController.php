<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Version;

class VersionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //
        $os = $request->os;
        if(!$os) $os = 0;
        $version = Version::where('os', $os)->first();
        return view('versions.edit', compact(['version']));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    // public function create()
    // {
    //     //
    //     $categories = Category::pluck('category','id');
    //     return view('rssurl.create', compact(['categories']));
    // }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    // public function store(Request $request)
    // {
    //     //
    //     $data = $request->all();
    //     unset($data['_token']);
    //     $rs = Version::insertGetId($data);
    //     if($rs) {
    //         return redirect(route('versions.index'));
    //     }else{
    //         return back();
    //     }
    // }

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
    // public function edit($id)
    // {
    //     //
    //     $rssurl = Version::find($id);
    //     $categories = Category::pluck('category','id');
    //     return view('rssurl.edit', compact(['rssurl','categories']));
    // }

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
        $rs = Version::find($id)->update($data);
        if($rs) {
            return redirect(route('versions.index'));
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
        Version::destroy($id);
        return redirect(route('versions.index'));
    }
    public function api_version(Request $request){
        $user_id = get_user_id($request);
        $version = Version::find(1);
        $data["statusCode"] = 0;
        $data["message"] = "";
        $data['data'] = [
            'version'=> $version->version,
            'status'=> $version->status,
            'message'=> $version->message,
        ];
        // if($user_id == 3) {
        //     $data['data'] = [
        //     'version'=> $version->version,
        //     'status'=> 1,
        //     'message'=> $version->message,
        // ];
        // }
        return response()->json($data);
    }
    public function api_version_android(Request $request){
        $user_id = get_user_id($request);
        $version = Version::find(2);
        $data["statusCode"] = 0;
        $data["message"] = "";
        $data['data'] = [
            'version'=> $version->version,
            'status'=> $version->status,
            'message'=> $version->message,
        ];
        // if($user_id == 3) {
        //     $data['data'] = [
        //     'version'=> $version->version,
        //     'status'=> 1,
        //     'message'=> $version->message,
        // ];
        // }
        return response()->json($data);
    }
}
