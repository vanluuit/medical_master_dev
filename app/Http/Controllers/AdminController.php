<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Crypt;
use Illuminate\Http\Request;
use App\User;
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


class AdminController extends Controller
{
    

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $users = User::where('roles',1);
        $users = $users->orderBy('id', 'DESC')->paginate(20);
        return view('admin.index',compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.create');
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
                'email' => 'required|email',
                'nickname' => 'required',
                'password' => 'required',
            ],
            [
                'email.required' => 'メールアドレスが間違っています。',
                'email.email' => 'メールアドレスが間違っています。',
                'nickname.required' => 'ニックネームが間違っています。',
                'password.required' => 'パスワードは8文字以上で入力してください。',
            ]
        );
        $check = User::where('email',$requests->email)->count();
        if($check) {
            $validator->after(function ($validator) {
                 $validator->errors()->add('email.email', 'このメールアドレスは既に利用されています。');
            });
        }
        if ($validator->fails()) {
            
            return redirect(route('users.create'))
                        ->withErrors($validator)
                        ->withInput();
        }

        $data = $requests->all();
        unset($data['_token']);
        unset($data['_method']);
        unset($data['password_conf']);
        $data['password'] = Crypt::encryptString($data['password']);
        $data['token'] = Crypt::encryptString(date('Y-m-d'));
        $data['roles'] = 1;
        
        $rs = User::insertGetId($data);
        if($rs) {
            return redirect(route('admin.index'));
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
        // $user = User::find($id);
        $user = User::where('id', $id)->first();
        $user->password = Crypt::decryptString($user->password);
        // $user->token = Crypt::encryptString(date('Y-m-d'));

        return view('admin.edit', compact('user'));

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
                'email' => 'required|email',
                'nickname' => 'required',
                'password' => 'required',
            ],
            [
                'email.required' => 'メールアドレスが間違っています。',
                'email.email' => 'メールアドレスが間違っています。',
                'nickname.required' => 'ニックネームが間違っています。',
                'password.required' => 'パスワードは8文字以上で入力してください。',
            ]
        );
        if ($validator->fails()) {
            
            return back()
                        ->withErrors($validator)
                        ->withInput();
        }

        
        $data = $requests->all();
        unset($data['_token']);
        unset($data['password_conf']);
        $data['password'] = Crypt::encryptString($data['password']); 
        // $data['token'] = Crypt::encryptString(date('Y-m-d'));       
        $rs = User::find($id)->update($data);
        if($rs) {
            return redirect(route('admin.index'));
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
        if($id != 1) User::destroy($id);
        return redirect(route('admin.index'));
    }


    
}
