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


class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function login()
    {
        // echo Crypt::decryptString('eyJpdiI6InpJV3F1MzdiWThGQUxtWFNSSiswV0E9PSIsInZhbHVlIjoiaEdmSUR5XC9JOUdRZTVZbjFsa2xiWVE9PSIsIm1hYyI6ImZhZmMzMDI3YmRiNjc5NWY4OGY2YmYyYjkxZDllYTNiNTUzZTM0YjY5ZWVhMzZiYTg4YjQwNTQyNGI4YTZkMDUifQ==');
        return view('users.login');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function doLogin(Request $requests)
    {
        $is_login = User::where('email', $requests->email)->where('roles', 1)->first();
        // dd($requests->email);
        if($is_login && Crypt::decryptString($is_login->password) == $requests->password) {
            session(['is_login'=> true]);
            session(['user'=> ['email'=>$is_login->email,'roles'=>$is_login->roles,'username'=>$is_login->username,'nickname'=>$is_login->nickname]]);
            return redirect(Route('associations.index'));
        }
        return back();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function Logout(Request $requests)
    {
        session()->forget(['is_login', 'user']);
        return redirect(route('login'));
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function userAnalytic(Request $request)
    {
        if(!@$request->start_day) {
            $start_day = date('Y-m-01');
        }else{
            $start_day = $request->start_day;
        }
        if(!@$request->end_day) {
            $end_day = date('Y-m-t');
        }else{
            $end_day = $request->end_day;
        }
        $categories = Category::with(['user_category'=>function($qr)use($start_day, $end_day){
            $qr->whereHas('user', function($qrs)use($start_day, $end_day){
                $qrs->where('created_at', '>', $start_day)
                ->where('created_at', '<=', $end_day.' 23:59:59');
            });
        }])
        ->get();

        return view('users.analytic',compact('categories','start_day','end_day'));
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // $users = User::with(['association'])->has('association')->pluck('id');
        // $users = User::whereNotIn('id',$users)->pluck('id');
        // $devices = Device::whereIn('user_id', $users)->delete();
        $categories = Category::pluck('category','id');
        $users = User::with(['association'=>function($qr){
            $qr->with(['category', 'member'])->where('status', 1);
        }])->where('roles',2);
        if($request->s) {
            $users = $users->where('email','LIKE', '%'.$request->s.'%')
                ->orWhere('nickname', 'LIKE', '%'.$request->s.'%')
                ->orWhere('firstname', 'LIKE', '%'.$request->s.'%')
                ->orWhere('lastname', 'LIKE', '%'.$request->s.'%')
                ->orWhere('firstname_k', 'LIKE', '%'.$request->s.'%')
                ->orWhere('lastname_k', 'LIKE', '%'.$request->s.'%')
                ->orWhere('birthday', 'LIKE', '%'.$request->s.'%')
                ->orWhere('zip', 'LIKE', '%'.$request->s.'%')
                ->orWhere('area_hospital', 'LIKE', '%'.$request->s.'%')
                ->orWhere('city_hospital', 'LIKE', '%'.$request->s.'%')
                ->orWhere('hospital_name', 'LIKE', '%'.$request->s.'%')
                ->orWhere(function($qr)use($request){
                    $qr->whereHas('career', function($qrs)use($request){
                        $qrs->where('name','LIKE', '%'.$request->s.'%');
                    });
                });
        }
        $users = $users->orderBy('id', 'DESC')->paginate(20);
        // dd($users);
        return view('users.index',compact('users','categories'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $careers = Career::pluck('name','id');
        $facultys = Faculty::pluck('name','id');
        $area_hospital = Province::pluck('province_name','province_name');
        $categories = Category::pluck('category','id');
        return view('users.create', compact(['careers','facultys','categories','area_hospital']));
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
                'hospital_name' => 'required',
            ],
            [
                'email.required' => 'メールアドレスが間違っています。',
                'email.email' => 'メールアドレスが間違っています。',
                'nickname.required' => 'ニックネームが間違っています。',
                'password.required' => 'パスワードは8文字以上で入力してください。',
                'hospital_name.required' => '勤め先の病院・医療機関名を入力してください。'
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

        if($requests->association_id) {
            foreach ($requests->association_id as $key => $value) {
                if($value > 0 ){
                    $member_id = Member::where('code', $requests->member_code[$key])->first();
                    if(!$member_id) {
                        $validator->after(function ($validator) {
                            $validator->errors()->add('member_code.required', '学会選択が間違っています。');
                        });
                    }
                }
                
            }
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
        unset($data['association_id']);
        unset($data['member_code']);
        unset($data['avatar']);
        unset($data['association_id']);
        unset($data['member_code']);
        $data['password'] = Crypt::encryptString($data['password']);
        $data['token'] = Crypt::encryptString(date('Y-m-d'));
        $data['roles'] = 2;
        $data['status'] = 1;
        
        $rs = User::insertGetId($data);
        if($rs) {
            if($requests->avatar){
                $path = '/storage/app/'.@$requests->file('avatar')->store('users');
                $users = User::find($rs);
                $users->avatar = $path;
                $users->save();
            }
            if($requests->association_id) {
                foreach ($requests->association_id as $key => $value) {
                    $member_id = Member::where('code', $requests->member_code[$key])->first();
                    $cate = new UserCategory;
                    $cate->category_id = $requests->association_id[$key];
                    $cate->member_id = $member_id->id;
                    $cate->user_id = $rs;
                    $cate->status = 1;
                    $cate->save();
                }
            }
            return redirect(route('users.index'));
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
        $user = User::with(['career','faculty','association.category','association.member'])->where('id', $id)->first();
        $user->password = Crypt::decryptString($user->password);
        $user->token = Crypt::encryptString(date('Y-m-d'));
        $careers = Career::pluck('name','id');
        $facultys = Faculty::pluck('name','id');
        $categories = Category::pluck('category','id');
        return view('users.show', compact('user','careers','facultys','categories'));
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
        $user = User::with(['career','faculty','association.category','association.member','association'])->where('id', $id)->first();
        $notcate = UserCategory::where('user_id', $id)->pluck('category_id');
        // dd($notcate);
        $user->password = Crypt::decryptString($user->password);
        $user->token = Crypt::encryptString(date('Y-m-d'));
        $careers = Career::pluck('name','id');
        $facultys = Faculty::pluck('name','id');
        $area_hospital = Province::pluck('province_name','province_name');
        $categories = Category::whereNotIn('id', $notcate)->pluck('category','id');
        $association = [];
        if($user->association) {
            foreach ($user->association as $key => $value) {
                $association[] = $value->category_id;
            }
            $associ= json_encode($association);
        }
        return view('users.edit', compact('user','careers','facultys','categories','associ','area_hospital'));
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
                // 'email' => 'required|email',
                'nickname' => 'required',
                'password' => 'required',
                'association_id' => 'required',
                'member_code' => 'required',
                'hospital_name' => 'required',
            ],
            [
                // 'email.required' => 'メールアドレスが間違っています。',
                // 'email.email' => 'メールアドレスが間違っています。',
                'nickname.required' => 'ニックネームが間違っています。',
                'password.required' => 'パスワードは8文字以上で入力してください。',
                'association_id.required' => '学会選択が間違っています。',
                'member_code.required' => '学会選択が間違っています。',
                'hospital_name.required' => '勤め先の病院・医療機関名を入力してください。'
            ]
        );
        if ($validator->fails()) {
            
            return back()
                        ->withErrors($validator)
                        ->withInput();
        }

        if($requests->association_id) {
            foreach ($requests->association_id as $key => $value) {
                if($value > 0 ){
                    $member_id = Member::where('code', $requests->member_code[$key])->first();
                    if(!$member_id) {
                        $validator->after(function ($validator) {
                            $validator->errors()->add('member_code.required', '学会選択が間違っています。');
                        });
                    }
                }
            }
        }
        if ($validator->fails()) {
            
            return back()
                        ->withErrors($validator)
                        ->withInput();
        }

        $data = $requests->all();
        unset($data['_token']);
        unset($data['password_conf']);
        unset($data['association_id']);
        unset($data['member_code']);
        unset($data['avatar']);
        unset($data['association_id']);
        unset($data['member_code']);
        $data['password'] = Crypt::encryptString($data['password']); 
        $data['token'] = Crypt::encryptString(date('Y-m-d'));       
        $rs = User::find($id)->update($data);
        if($rs) {
            
            if($requests->avatar){
                $user = User::find($id);
                $avata_u = $user->avatar;
                $avata_u = str_replace('/storage/app/', '', $avata_u);
                $path = '/storage/app/'.@$requests->file('avatar')->store('users');
                $users->avatar = $path;
                $users->save();
                Storage::delete($avata_u);
            }
            if($requests->association_id) {
                foreach ($requests->association_id as $key => $value) {
                    if($value > 0 ){
                        $member_id = Member::where('code', $requests->member_code[$key])->first();
                        $cate = new UserCategory;
                        $cate->category_id = $requests->association_id[$key];
                        $cate->member_id = $member_id->id;
                        $cate->user_id =$id;
                        $cate->save();
                    }
                    
                }
            }
            return redirect(route('users.index'));
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
        User::destroy($id);
        DB::table('user_categories')->where('user_id', $id)->delete();
        DB::table('user_channels')->where('user_id', $id)->delete();
        DB::table('user_discussions')->where('user_id', $id)->delete();
        DB::table('user_events')->where('user_id', $id)->delete();
        DB::table('user_news')->where('user_id', $id)->delete();
        DB::table('user_questions')->where('user_id', $id)->delete();
        DB::table('user_topics')->where('user_id', $id)->delete();
        DB::table('user_videos')->where('user_id', $id)->delete();
        DB::table('view_channels')->where('user_id', $id)->delete();
        DB::table('view_news')->where('user_id', $id)->delete();
        DB::table('view_videos')->where('user_id', $id)->delete();
        DB::table('close_questions')->where('user_id', $id)->delete();
        DB::table('comment_discussions')->where('user_id', $id)->delete();
        DB::table('comment_discussion_report')->where('user_id', $id)->delete();
        DB::table('comment_news')->where('user_id', $id)->delete();
        DB::table('devices')->where('user_id', $id)->delete();
        DB::table('like_channels')->where('user_id', $id)->delete();
        DB::table('like_comments')->where('user_id', $id)->delete();
        DB::table('like_discussions')->where('user_id', $id)->delete();
        DB::table('like_news')->where('user_id', $id)->delete();
        DB::table('like_videos')->where('user_id', $id)->delete();
        return redirect(route('users.index'));
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function ajaxpro(Request $request)
    {
        // $user = User::find($id);
        $user = User::find($request->id);
        $user->pro = $request->pro;
        $user->save();
        echo $user->pro; die();
    }


    public function api_list_province()
    {
        // $categories = Category::select(['id','category','code'])->where('publish', 0)->get(); 
        $area_hospital = Province::pluck('province_name');
        if(count($area_hospital)) {
            $data["statusCode"] = 0;
            $data["message"] = "";
            $data['data'] = $area_hospital;  
        }else{
            $data["statusCode"] = 0;
            $data["message"] = "";
            $data['data'] = $area_hospital;
        }
        
        
        return response()->json($data);
    }

    // api code
    public function api_authentication_email(Request $request)
    {
        if(!$request->email) {
            $data["statusCode"] = 1;
            $data["message"] = "Email parameters do not exist";
            return response()->json($data);
        }
        $user = User::where('email',$request->email)->count();
        if($user) {
            $user_id = User::where('email',$request->email)->first()->id;
            $association = UserCategory::where('user_id', $user_id)->where('status', 1)->get();
            if(count($association)) {
                $validator = Validator::make(
                  $request->all(), 
                  [
                  ],
                  [
                  ]
              );
                $validator->after(function ($validator) {
                    $validator->errors()->add('email.email', '既に登録済みのメールアドレスです');
                });
                $data["statusCode"] = 1;
                $data["message"] = "既に登録済みのメールアドレスです";
                return response()->json($data);
            }
            
        }
        
        $mail = $request->email;
        $authentication_code = generateRandomCode(6);
        VeryMail::updateOrCreate(['email'=>$mail],['code'=>$authentication_code]);
        $rsm = Mail::send('users.mailregis', ['text' => $authentication_code], function ($m) use ($mail) {
                $m->to($mail, 'MEDICAL MASTERS運営事務局')->subject('MEDICAL MASTERS運営事務局');
            });
        $data["statusCode"] = 0;
        $data["message"] = "";
        // $data['data'] = ['authentication_code' => $authentication_code];
        return response()->json($data);
    }

    public function api_check_email_code(Request $request)
    {
        $check = VeryMail::where('email', @$request->email)->where('code', @$request->code)->first();
        if(!$check) {
            $data["statusCode"] = 1;
            $data["message"] = "wrong code";
            return response()->json($data);
        }
        User::where('email', @$request->email)->update(['status'=> 1]);
        $data["statusCode"] = 0;
        $data["message"] = "";  
        return response()->json($data);
    }

    public function api_register(Request $request)
    {
        
        // return response()->json($request->nickname);
        $validator = Validator::make(
            $request->all(), 
            [
                'email' => 'required|email',
                'password' => 'required|min:6',
                // 'association' => 'required',
                // 'nickname' => 'required',
                // 'firstname' => 'required',
                // 'lastname' => 'required',
                // 'birthday' => 'required',
            ],
            [
                'email.required' => 'メールアドレスが間違っています。',
                'email.email' => 'メールアドレスが間違っています。',
                'password.required' => 'パスワードは6文字以上で入力してください。',
                'password.min' => 'パスワードは6文字以上で入力してください。',
                // 'association.required' => '学会選択が間違っています。',
                // 'nickname.required' => '綽名が間違っています。',
                // 'firstname.required' => '姓を入力してください。',
                // 'lastname.required' => '名を入力してください。',
                // 'birthday.required' => '生年月日を入力してください。',
            ]
        );
        if ($validator->fails()) {
            // $arr = $validator->errors();
            $errorString = implode("\n",$validator->messages()->all());
            $data["statusCode"] = 1;
            $data["message"] = $errorString;
            $data['validator'] = $validator->errors();
            return response()->json($data);
        }

        $check = User::where('email',$request->email)->count();
        if($check) {
            $user_id = User::where('email',$request->email)->first()->id;
            $association = UserCategory::where('user_id', $user_id)->where('status', 1)->get();
            if(count($association)) {
                $validator->after(function ($validator) {
                    $validator->errors()->add('email.email', '既に登録済みのメールアドレスです');
                });
            }else{
                $user = [];
                $user['email'] = $request->email;
                $user['password'] = Crypt::encryptString($request->password);
                $user['nickname'] = $request->nickname;
                $user['firstname'] = $request->firstname;
                $user['lastname'] = $request->lastname;
                $user['birthday'] = $request->birthday;
                $user['career_id'] = $request->career_id;
                
                $user['roles'] = 2;
                $user['token'] = Crypt::encryptString(date('Y-m-d H:i:s'));
                User::where('id', $user_id)->update($user);
                $statusAssociation = 0;
                if($request->association) {
                    $cs = json_decode($request->association, true);
                    foreach ($cs as $key => $value) {
                        if(is_array($value)){
                            foreach ($value as $key1 => $value1) {
                                $member_id = Member::where('code', $value1)->where('category_id', $key1)->where('status', 1)->first();
                                if($member_id) {
                                    $statusAssociation = 1;
                                } else{
                                    $ck = ['code' => $value1];
                                    $datam = ['status' => 0, 'category_id' => $key1 ];
                                    $mem_id = Member::updateOrCreate($ck, $datam);
                                    $datamail['nickname'] = $request->nickname;
                                    $datamail['hh'] = @Category::find($key1)->category;
                                    $this->send_mail_request($user->email, $datamail);
                                }

                                ($member_id) ? $member_idhh = $member_id->id : $member_idhh = $mem_id->id;
                                ($member_id) ? $statushh = 1 : $statushh = 0;
                                ($member_id) ? $typehh = 1 : $typehh = 0;
                                $ckhh = [
                                    'category_id'=>$key1,
                                    'user_id'=>$user->id,
                                ];
                                $datahh = [
                                    'member_id'=>$member_idhh,
                                    'status'=>$statushh,
                                    'type'=>$typehh,
                                    'request_date'=>date('Y-m-d H:i:s'),
                                ];
                                UserCategory::updateOrCreate($ckhh, $datahh);
                            }
                        }else{
                            $member_id = Member::where('code', $value)->where('category_id', $key)->where('status', 1)->first();
                            if($member_id) {
                                $statusAssociation = 1;
                            } else{
                                $ck = ['code' => $value];
                                $datam = ['status' => 0, 'category_id' => $key ];
                                $mem_id = Member::updateOrCreate($ck, $datam);
                                $datamail['nickname'] = $request->nickname;
                                $datamail['hh'] = @Category::find($key)->category;
                                $this->send_mail_request($user->email, $datamail);       
                            }
                            ($member_id) ? $member_idhh = $member_id->id : $member_idhh = $mem_id->id;
                            ($member_id) ? $statushh = 1 : $statushh = 0;
                            ($member_id) ? $typehh = 1 : $typehh = 0;
                            $ckhh = [
                                'category_id'=>$key,
                                'user_id'=>$user->id,
                            ];
                            $datahh = [
                                'member_id'=>$member_idhh,
                                'status'=>$statushh,
                                'type'=>$typehh,
                                'request_date'=>date('Y-m-d H:i:s'),
                            ];
                            UserCategory::updateOrCreate($ckhh, $datahh);
                        }
                    }
                }
                User::where('id', $user_id)->update(['status'=> $statusAssociation]);
                $data["statusCode"] = 0;
                $data["message"] = "";
                $data['data'] = ['token' => $user['token'], 'status' => $statusAssociation];
                return response()->json($data);
            }

            
        }
        // $c_code = VeryMail::where('email', $request->email)->where('code', $request->code);

        if ($validator->fails()) {
            // $arr = $validator->errors();
            $errorString = implode("\n",$validator->messages()->all());
            $data["statusCode"] = 1;
            $data["message"] = $errorString;
            $data['validator'] = $validator->errors();
            return response()->json($data);
        }

        

        $req = $request->all();
        $user = new User;
        $user->email = $request->email;
        $user->password = Crypt::encryptString($request->password);
        $user->nickname = $request->nickname;
        $user->firstname = $request->firstname;
        $user->lastname = $request->lastname;
        $user->birthday = $request->birthday;
        $user->career_id = $request->career_id;
        $user->roles = 2;
        $user->token = Crypt::encryptString(date('Y-m-d H:i:s'));
        $user->save();
        $statusAssociation = 0;
        if($request->association) {
            $cs = json_decode($request->association, true);
            foreach ($cs as $key => $value) {
                if(is_array($value)){
                    foreach ($value as $key1 => $value1) {
                        $member_id = Member::where('code', $value1)->where('category_id', $key1)->where('status', 1)->first();
                        if($member_id) {
                            $statusAssociation = 1;
                        } else{
                            $ck = ['code' => $value1];
                            $datam = ['status' => 0, 'category_id' => $key1 ];
                            $mem_id = Member::updateOrCreate($ck, $datam);
                            $datamail['nickname'] = $request->nickname;
                            $datamail['hh'] = @Category::find($key1)->category;
                            $this->send_mail_request($user->email, $datamail);
                        }

                        ($member_id) ? $member_idhh = $member_id->id : $member_idhh = $mem_id->id;
                        ($member_id) ? $statushh = 1 : $statushh = 0;
                        ($member_id) ? $typehh = 1 : $typehh = 0;
                        $ckhh = [
                            'category_id'=>$key1,
                            'user_id'=>$user->id,
                        ];
                        $datahh = [
                            'member_id'=>$member_idhh,
                            'status'=>$statushh,
                            'type'=>$typehh,
                            'request_date'=>date('Y-m-d H:i:s'),
                        ];
                        UserCategory::updateOrCreate($ckhh, $datahh);
                    }
                }else{
                    $member_id = Member::where('code', $value)->where('category_id', $key)->where('status', 1)->first();
                    if($member_id) {
                        $statusAssociation = 1;
                    } else{
                        $ck = ['code' => $value];
                        $datam = ['status' => 0, 'category_id' => $key ];
                        $mem_id = Member::updateOrCreate($ck, $datam);
                        $datamail['nickname'] = $request->nickname;
                        $datamail['hh'] = @Category::find($key)->category;
                        $this->send_mail_request($user->email, $datamail);       
                    }
                    ($member_id) ? $member_idhh = $member_id->id : $member_idhh = $mem_id->id;
                    ($member_id) ? $statushh = 1 : $statushh = 0;
                    ($member_id) ? $typehh = 1 : $typehh = 0;
                    $ckhh = [
                        'category_id'=>$key,
                        'user_id'=>$user->id,
                    ];
                    $datahh = [
                        'member_id'=>$member_idhh,
                        'status'=>$statushh,
                        'type'=>$typehh,
                        'request_date'=>date('Y-m-d H:i:s'),
                    ];
                    UserCategory::updateOrCreate($ckhh, $datahh);
                }
            }
        }
        User::where('id', $user->id)->update(['status'=> $statusAssociation]);
        
        $data["statusCode"] = 0;
        $data["message"] = "";
        $data['data'] = ['token' => $user->token, 'status' => $statusAssociation];
        
        return response()->json($data);
    }

    

    public function api_change_password(Request $request)
    {
        $user_id = get_user_id($request);
        $token = Crypt::encryptString(date('Y-m-d H:i:s'));
        $userol = User::find($user_id);
        $pass = Crypt::decryptString($userol->password);
        if($pass != $request->oldpass) {
            $data["statusCode"] = 1;
            $data["message"] = 'wrong old password';
        }
        $dl = [
            'password' =>  Crypt::encryptString(@$request->password),
            'token' =>  $token,
        ];
        User::where('id',$user_id)->update($dl);
        $data["statusCode"] = 0;
        $data["message"] = "";
        $data['data'] = ['token' => $token];
        
        return response()->json($data);
    }

    public function api_edit(Request $request)
    {
        
        // $validator = Validator::make(
        //     $request->all(), 
        //     [
        //         'hospital_name' => 'required',
        //     ],
        //     [
        //         'hospital_name.required' => '勤め先の病院・医療機関名を入力してください。'
        //     ]
        // );
        // if ($validator->fails()) {
        //     // $arr = $validator->errors();
        //     $errorString = implode("\n",$validator->messages()->all());
        //     $data["statusCode"] = 1;
        //     $data["message"] = $errorString;
        //     $data['validator'] = $validator->errors();
        //     return response()->json($data);
        // }
        // return response()->json($request);
        $user_id = get_user_id($request);
        $dl = [
            'firstname' => @$request->firstname,
            'lastname' => @$request->lastname,
            'firstname_k' => @$request->firstname_k,
            'lastname_k' => @$request->lastname_k,
            'birthday' => @$request->birthday,
            'career_id' => @$request->career_id,
            'hospital_name' => @$request->hospital_name,
            'area_hospital' => @$request->area_hospital,
            'city_hospital' => @$request->city_hospital,
            'faculty_id' => @$request->faculty_id,
            'nickname' => @$request->nickname,
        ];
        User::where('id',$user_id)->update($dl);
        $user = User::find($user_id);
        if($request->avatar){
            $avata_u = $user->avatar;
            $avata_u = str_replace('/storage/app/', '', $avata_u);
            $path = '/storage/app/'.@$request->file('avatar')->store('users');
            $users = User::find($user->id);
            $users->avatar = $path;
            $users->save();
            Storage::delete($avata_u);
        }
        if($request->association) {
            $cs = json_decode($request->association, true);
            foreach ($cs as $key => $value) {
                if(is_array($value)){
                    foreach ($value as $key1 => $value1) {
                        $member_id = Member::where('code', $value1)->where('category_id', $key1)->where('status', 1)->first();
                        if($member_id) {
                            $statusAssociation = 1;
                        } else{
                            $ck = ['code' => $value1];
                            $datam = ['status' => 0, 'category_id' => $key1 ];
                            $mem_id = Member::updateOrCreate($ck, $datam);
                            $datamail['nickname'] = $request->nickname;
                            $datamail['hh'] = @Category::find($key1)->category;
                            $this->send_mail_request($user->email, $datamail);
                        }

                        ($member_id) ? $member_idhh = $member_id->id : $member_idhh = $mem_id->id;
                        ($member_id) ? $statushh = 1 : $statushh = 0;
                        ($member_id) ? $typehh = 1 : $typehh = 0;
                        $ckhh = [
                            'category_id'=>$key1,
                            'user_id'=>$user->id,
                        ];
                        $datahh = [
                            'member_id'=>$member_idhh,
                            'status'=>$statushh,
                            'type'=>$typehh,
                            'request_date'=>date('Y-m-d H:i:s'),
                        ];
                        UserCategory::updateOrCreate($ckhh, $datahh);
                    }
                }else{
                    $member_id = Member::where('code', $value)->where('category_id', $key)->where('status', 1)->first();
                    if($member_id) {
                        $statusAssociation = 1;
                    } else{
                        $ck = ['code' => $value];
                        $datam = ['status' => 0, 'category_id' => $key ];
                        $mem_id = Member::updateOrCreate($ck, $datam);
                        $datamail['nickname'] = $request->nickname;
                        $datamail['hh'] = @Category::find($key)->category;
                        $this->send_mail_request($user->email, $datamail);       
                    }
                    ($member_id) ? $member_idhh = $member_id->id : $member_idhh = $mem_id->id;
                    ($member_id) ? $statushh = 1 : $statushh = 0;
                    ($member_id) ? $typehh = 1 : $typehh = 0;
                    $ckhh = [
                        'category_id'=>$key,
                        'user_id'=>$user->id,
                    ];
                    $datahh = [
                        'member_id'=>$member_idhh,
                        'status'=>$statushh,
                        'type'=>$typehh,
                        'request_date'=>date('Y-m-d H:i:s'),
                    ];
                    UserCategory::updateOrCreate($ckhh, $datahh);
                }
            }
        }
        $data["statusCode"] = 0;
        $data["message"] = "";
        // $data['data'] = ['token' => $user->token];
        
        return response()->json($data);
    }
    public function api_user_device_token(Request $request){
        $user_id = get_user_id($request);
        if($request->deviceToken) {
            $ck = [
                'token'=> $request->deviceToken,
            ];
            $data_tk = ['user_id'=> $user_id];
            if($request->Flatform) {
                $data_tk['Flatform'] = $request->Flatform;
            }
            if($request->userDevice) {
                $data_tk['userDevice'] = $request->userDevice;
            }
            if($request->OS) {
                $data_tk['OS'] = $request->OS;
            }
            if($request->appVersion) {
                $data_tk['appVersion'] = $request->appVersion;
            }
            $r = Device::updateOrCreate($ck,$data_tk);
        }
        $data["statusCode"] = 0;
        $data["message"] = "";
        return response()->json($data);
    }
    public function api_login(Request $request)
    {
        $validator = Validator::make(
            $request->all(), 
            [
                'email' => 'required|email',
                'password' => 'required|min:6',
            ],
            [
                'email.required' => 'メールアドレスが間違っています。',
                'email.email' => 'メールアドレスが間違っています。',
                'password.required' => 'パスワードは6文字以上で入力してください。',
                'password.min' => 'パスワードは6文字以上で入力してください。',
            ]
        );
        // $token = $request->header('Authorization');
        // $token = str_replace('bearer ', '', $token);
        // $token = str_replace('bearer', '', $token);
        // $token = str_replace(' bearer ', '', $token);
        // $token = trim($token);

        $is_login = User::where('email', $request->email)->where('roles', 2)->first();
        
        if($is_login && Crypt::decryptString($is_login->password) == $request->password) {
            $token = Crypt::encryptString(date('Y-m-d H:i:s'));
            $user = User::where('id', $is_login->id);
            if($is_login->token == "") {
                User::where('id', $is_login->id)->update(['token'=>$token]);
            }else{
                $token = $is_login->token;
            }
            if($request->deviceToken) {
                $ck = [
                    'token'=> $request->deviceToken,
                ];
                $data_tk = ['user_id'=> $is_login->id];
                $r = Device::updateOrCreate($ck,$data_tk);
            }
            $user_categorys = UserCategory::where('user_id', $is_login->id)->get();
            if(count($user_categorys)) {
                $data["statusCode"] = 0;
                $data["message"] = "";
                $data['data'] = ['token' => $token, 'info'=>$is_login];
            }else{
                $data["statusCode"] = 1;
                $data["message"] = "日本呼吸器学会でのパイロットテストは終了しているため、\nこのテストアカウントは無効になっています。\nテストにご協力いただき、ありがとうございました。";
            }
            
        }else{
            $data["statusCode"] = 1;
            $data["message"] = "入力されたメールアドレスか、パスワードが間違っています。";
        }
        return response()->json($data);
    }
    public function api_logout(Request $request)
    {
        
        $token = $request->header('Authorization');
        $token = str_replace('bearer ', '', $token);
        $token = str_replace('bearer', '', $token);
        $token = str_replace(' bearer ', '', $token);
        $is_login = User::where('token', $token)->where('roles', 2)->first();

        if($is_login) {
            $token = '';
            $user = User::where('id', $is_login->id)->update(['token'=> $token]);
            $data["statusCode"] = 0;
            $data["message"] = "";
        }else{
            $data["statusCode"] = 1;
            $data["message"] = "401 Unauthorized";
        }
        return response()->json($data);
    }
    public function api_info(Request $request)
    {
        
        $token = $request->header('Authorization');
        $token = str_replace('bearer ', '', $token);
        $token = str_replace('bearer', '', $token);
        $token = str_replace(' bearer ', '', $token);
        $is_login = User::with([
            'career',
            'faculty',
            'association'=>function($qr){
                $qr->where('status', '<', 2);
            },
            'association.category',
            'association.member'
        ])->where('token', $token)->get();
        if($is_login) {
            $token = '';
            $data["statusCode"] = 0;
            $data["message"] = "";
            $data['data'] = $is_login;
        }else{
            $data["statusCode"] = 1;
            $data["message"] = "401 Unauthorized";
        }
        return response()->json($data);
    }

    public function api_reset_password(Request $request)
    {
        $validator = Validator::make(
            $request->all(), 
            [
                'email' => 'required|email',
                'birthday' => 'required',
            ],
            [
                'email.required' => 'メールアドレスが間違っています。',
                'email.email' => 'メールアドレスが間違っています。',
                'birthday.required' => '生年月日が間違っています。'
            ]
        );
        if ($validator->fails()) {
            $data["statusCode"] = 1;
            $data["message"] = "Error occurred when reset password";
            $data['validator'] = $validator->errors();
            return response()->json($data);
        }
        
        $user = User::where('email',$request->email)->where('birthday',$request->birthday)->first();
        if($user) {
            $password = generateRandomCode(10);
            $user = $user->update(['password'=>Crypt::encryptString($password)]);
            $mail = $request->email;
            $rsm = Mail::send('users.mailtemp', ['text' => 'パスワード：'.$password], function ($m) use ($mail) {
                $m->to($mail, 'Medical')->subject('Medical');
            });
            $data["statusCode"] = 0;
            $data["message"] = "";
        }else{
            $data["statusCode"] = 1;
            $data["message"] = "Incorrect email or birthday";
        }
        return response()->json($data);
    }

    public function api_avatar_set(Request $request)
    {
        
        $token = $request->header('Authorization');
        $token = str_replace('bearer ', '', $token);
        $token = str_replace('bearer', '', $token);
        $token = str_replace(' bearer ', '', $token);
        $is_login = User::select(['id','avatar'])->where('token', $token)->first();

        if($request->avatar && $is_login){
            $avata_u = $is_login->avatar;
            $avata_u = str_replace('/storage/app/', '', $avata_u);
            $path = '/storage/app/'.@$request->file('avatar')->store('users');
            $users = User::find($is_login->id);
            $users->avatar = $path;
            $users->save();
            Storage::delete($avata_u);
            $data["statusCode"] = 0;
            $data["message"] = "";
        }else{
            $data["statusCode"] = 1;
            $data["message"] = "401 Unauthorized";
        }
        return response()->json($data);
    }
    public function out_time(Request $request)
    {
        $user_id = get_user_id($request);
        $dl = [
            'time_out' => date('Y-m-d H:i:s')
        ];
        User::where('id',$user_id)->update($dl);
        $data["statusCode"] = 0;
        $data["message"] = "";
        // $data['data'] = ['token' => $user->token];
        
        return response()->json($data);
    }

    public function api_request_confirm(Request $request)
    {
        $user_id = get_user_id($request);
        $user = User::find($user_id);
        $statusAssociation = 0;
        if($request->association) {
            $cs = json_decode($request->association, true);
            foreach ($cs as $key => $value) {
                if(is_array($value)){
                    foreach ($value as $key1 => $value1) {
                        $member_id = Member::where('code', $value1)->where('category_id', $key1)->where('status', 1)->first();
                        if($member_id) {
                            $statusAssociation = 1;
                        } else{
                            $ck = ['code' => $value1];
                            $datam = ['status' => 0, 'category_id' => $key1 ];
                            $mem_id = Member::updateOrCreate($ck, $datam);
                            $datamail['nickname'] = $request->nickname;
                            $datamail['hh'] = @Category::find($key1)->category;
                            $this->send_mail_request($user->email, $datamail);
                        }

                        ($member_id) ? $member_idhh = $member_id->id : $member_idhh = $mem_id->id;
                        ($member_id) ? $statushh = 1 : $statushh = 0;
                        ($member_id) ? $typehh = 1 : $typehh = 0;
                        $ckhh = [
                            'category_id'=>$key1,
                            'user_id'=>$user->id,
                        ];
                        $datahh = [
                            'member_id'=>$member_idhh,
                            'status'=>$statushh,
                            'type'=>$typehh,
                            'request_date'=>date('Y-m-d H:i:s'),
                        ];
                        UserCategory::updateOrCreate($ckhh, $datahh);
                    }
                }else{
                    $member_id = Member::where('code', $value)->where('category_id', $key)->where('status', 1)->first();
                    if($member_id) {
                        $statusAssociation = 1;
                    } else{
                        $ck = ['code' => $value];
                        $datam = ['status' => 0, 'category_id' => $key ];
                        $mem_id = Member::updateOrCreate($ck, $datam);
                        $datamail['nickname'] = $request->nickname;
                        $datamail['hh'] = @Category::find($key)->category;
                        $this->send_mail_request($user->email, $datamail);       
                    }
                    ($member_id) ? $member_idhh = $member_id->id : $member_idhh = $mem_id->id;
                    ($member_id) ? $statushh = 1 : $statushh = 0;
                    ($member_id) ? $typehh = 1 : $typehh = 0;
                    $ckhh = [
                        'category_id'=>$key,
                        'user_id'=>$user->id,
                    ];
                    $datahh = [
                        'member_id'=>$member_idhh,
                        'status'=>$statushh,
                        'type'=>$typehh,
                        'request_date'=>date('Y-m-d H:i:s'),
                    ];
                    UserCategory::updateOrCreate($ckhh, $datahh);
                }
            }
        }
        $data["statusCode"] = 0;
        $data["message"] = "";
        $data['data'] = ['status' => $statusAssociation];
        
        return response()->json($data);
    }

    public function api_check_assosication_login(Request $request){
        $user_id = get_user_id($request);
        $user_stt = User::find($user_id);
        if($user_stt->status == 0) {
             $status = 0;
        }else{
            $check = UserCategory::where('user_id', $user_id)->pluck('status')->toArray();
            $status = 0;
            if(in_array("1", $check)) $status = 1; // có thể login được
            else{
                if(in_array("0", $check)) $status = 0; // đợi nhập authen
                else $status = 2;// buột đăng ký lại
            }
        }
        $data["statusCode"] = 0;
        $data["message"] = "";
        $data['data'] = ['status' => $status];
        return response()->json($data);
    }

    public function send_mail_request($email, $data){
    	$title = '【MEDICAL MASTERS】会員登録をいただき誠にありがとうございます。';
    	return $rsm = Mail::send('users.mailrequest', ['data' => $data], function ($m) use ($email, $title) {
            $m->to($email, $title)->subject($title);
        });
    }


    public function user_register_back(Request $request){
        $user_id = get_user_id($request);
        User::destroy($user_id);
        UserCategory::where('user_id', $user_id)->delete();
        $data["statusCode"] = 0;
        $data["message"] = "";
        return response()->json($data);
    }

    public function export(Request $request){
        $careers = Career::pluck('name', 'id')->toArray();
        $faculty = Faculty::pluck('name', 'id')->toArray();
        $users = User::with(['association.member']);
        if($request->category_id) {
            $users = $users->whereHas('association', function($qr)use($request){
                $qr->where('category_id', $request->category_id)->where('status', 1);
            });
        }
        $users = $users->get();
        $titles = ['nickname', 'email', 'firstname', 'lastname', 'firstname_k', 'lastname_k', 'birthday', 'sex', 'career', 'faculty', 'area_hospital', 'city_hospital', 'hospital_name', 'member code'];
        $datas=[];
        foreach ($users as $key => $value) {
            $datas[$key]['nickname'] = $value->nickname;
            $datas[$key]['email'] = $value->email;
            $datas[$key]['firstname'] = $value->firstname;
            $datas[$key]['lastname'] = $value->lastname;
            $datas[$key]['firstname_k'] = $value->firstname_k;
            $datas[$key]['lastname_k'] = $value->lastname_k;
            $datas[$key]['birthday'] = $value->birthday;
            $datas[$key]['sex'] = sex()[$value->sex];
            $datas[$key]['career_id'] = @$careers[$value->career_id];
            $datas[$key]['faculty_id'] = @$faculty[$value->faculty_id];
            // $datas[$key]['zip'] = $value->zip;
            $datas[$key]['area_hospital'] = $value->area_hospital;
            $datas[$key]['city_hospital'] = $value->city_hospital;
            $datas[$key]['hospital_name'] = $value->hospital_name;
            if(count($value->association)) {
                $str = '';
                foreach ($value->association as $km => $member) {
                    if($member->member) {
                        $str .= $member->member->code;
                        if($km < count($value->association) -1) {
                            $str .= ',';
                        }
                    }
                    
                }
                $datas[$key]['member'] = $str;
            }
        }
        $this->exportF($titles, $datas, 'user_export.csv');
    }

    public static function exportF($titles=[], $datas=[], $filename='report.csv'){
        $cols = ['A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z','AA','AB','AC','AD','AE','AF','AG','AH','AI','AJ','AK','AL','AM','AN','AO','AP','AQ','AR','AS','AT','AU','AV','AW','AX','AY','AZ','BA','BB','BC','BD','BE','BF','BG','BH','BI','BJ','BK','BL','BM','BN','BO','BP','BQ','BR','BS','BT','BU','BV','BW','BX','BY','BZ'];
        if(count($datas)) {
          // dd($datas);
          $objPHPExcel = new \PHPExcel(); 
          foreach ($titles as $key => $title) {
            $objPHPExcel->getActiveSheet()->setCellValue($cols[$key].'1',$title); 
          }
          foreach ($datas as $keyR => $rows) {
            $r = $keyR+2;
            $c = 0;
            foreach ($rows as $keyC => $col) {
              $objPHPExcel->getActiveSheet()->setCellValue($cols[$c].$r,$col); 
              $c++;
            }
          }
          $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'CSV');
          header('Content-Encoding: UTF-8');
          header('Content-type: text/csv; charset=UTF-8');
          header("Content-Disposition: attachment; filename=".$filename);
          header("Pragma: no-cache");
          header("Expires: 0");
          header('Content-Transfer-Encoding: binary');
          echo "\xEF\xBB\xBF";
          $objWriter->save('php://output');
          exit();
        }
    }
}
