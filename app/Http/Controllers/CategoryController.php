<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Category;

use App\User;
use App\Device;

class CategoryController extends Controller
{
    // api
    
    public function api_list()
    {
        $categories = Category::select(['id','category','code'])->where('publish', 0)->get(); 
        if(count($categories)) {
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

    public function api_list_by_user(Request $request)
    {
        $user_id = get_user_id($request);

        $callback = function($query)use($user_id) {
            $query->where('user_id', $user_id)->where('status', 1);
        };
        $categories = Category::whereHas('user_category', $callback)->with(['user_category' => $callback])->select('id','category')->where('publish', 0)->get();
        foreach ($categories as $key => $value) {
            unset($categories[$key]->user_category);
        }
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
    public function index()
    {
        //
        $categories = Category::paginate(20);
        return view('associations.index', compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        return view('associations.create');
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
        $data['publish'] = 1;
        $rs = Category::insertGetId($data);
        $category = Category::find($rs);
        // $str = date('Y-m-d', strtotime($category->created_at))." ". date('H:i', strtotime($category->created_at)).' '.$category->category.'が新たに登録可能となりました。';

        // $devices = Device::where('token','!=',"")->get();
        // foreach ($devices as $key => $value) {
        //     notification($value->token, $str);
        // }

        if($rs) {
            return redirect(route('associations.index'));
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
        $category = Category::find($id);
        return view('associations.edit',compact(['category']));
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
        $category = Category::find($id);
        $category->category = $request->category;
        $category->save();
        return redirect(route('associations.index'));
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
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function ajaxpublish(Request $request)
    {
        // $user = User::find($id);
        $category = Category::find($request->id);
        $category->publish = $request->publish;
        $category->save();
        echo $category->publish; die();
    }
}
