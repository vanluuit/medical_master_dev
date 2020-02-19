<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Crypt;
use Illuminate\Http\Request;
use App\PushUser;
use App\VeryMail;
use UploadedFile;
use Illuminate\Support\Facades\Storage;
use App\Member;
use App\Career;
use App\Faculty;
use App\Category;
use App\UserCategory;
use App\Device;
use App\Province;


use Validator;
use Mail;
use DB;


class PushUserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $pushusers = PushUser::with(['category']);
        if($request->s) {
            $pushusers = $pushusers->where('username','LIKE', '%'.$request->s.'%');
        }
        $pushusers = $pushusers->orderBy('id', 'DESC')->paginate(20);
        return view('pushusers.index',compact('pushusers'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $cates = PushUser::pluck('category_id');
        $categories = Category::whereNotIn('id', $cates)->pluck('category','id');
        return view('pushusers.create', compact(['categories']));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $requests)
    {
        $validator = Validator::make(
            $requests->all(), 
            [
                'username' => 'required',
                'password' => 'required',
                'category_id' => 'required',
            ],
            [
                'username.required' => 'ユーザ名が間違っています。',
                'password.required' => 'パスワードは6文字以上で入力してください。',
                'category_id.required' => '学会選択が間違っています。'
            ]
        );
        $check = PushUser::where('username',$requests->username)->count();
        if($check) {
            $validator->after(function ($validator) {
                 $validator->errors()->add('username.required', 'このユーザ名は既に利用されています。');
            });
        }
        if ($validator->fails()) {
            return redirect(route('pushusers.create'))
                ->withErrors($validator)
                ->withInput();
        }

        $data = $requests->all();
        unset($data['_token']);
        unset($data['_method']);
        $data['password'] = Crypt::encryptString($data['password']);

        $rs = PushUser::insertGetId($data);
        if($rs) {
            return redirect(route('pushusers.index'));
        }else{
            return back();
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        // $user = PushUser::find($id);
        $user = PushUser::where('id', $id)->first();
        $user->password = Crypt::decryptString($user->password);
        $cates = PushUser::where('category_id', '<>',$user->category_id)->pluck('category_id');
        $categories = Category::whereNotIn('id', $cates)->pluck('category','id');

        return view('pushusers.edit', compact('user','categories'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $requests, $id)
    {
        $validator = Validator::make(
            $requests->all(), 
            [
                'password' => 'required',
            ],
            [
                'password.required' => 'パスワードは8文字以上で入力してください。'
            ]
        );
        if ($validator->fails()) {
            
            return back()
                        ->withErrors($validator)
                        ->withInput();
        }

        $data = $requests->all();
        unset($data['_token']);
        unset($data['method']);
        $data['password'] = Crypt::encryptString($data['password']);        
        $rs = PushUser::find($id)->update($data);
        if($rs) {
            return redirect(route('pushusers.index'));
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
        PushUser::destroy($id);
        return redirect(route('pushusers.index'));
    }
}
