<?php

namespace App\Modules\Push\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Http\Request;
use App\Category;
use App\UserCategory;
use App\Device;
use App\PushUser;
use App\User;
use App\Member;
use App\VeryMail;
use App\Career;
use App\Faculty;
use App\Province;
use Validator;
use Mail;
use DB;
use Carbon\Carbon;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function login()
    {
        // echo Crypt::decryptString('eyJpdiI6IjJnWG5OaGFCenVOQkJKNEl3cmdXbmc9PSIsInZhbHVlIjoiQmVuQVVDTWx3eW9cL0tEVkRpOGtpRVE9PSIsIm1hYyI6Ijg5MDBmN2Q0MmIxNWQzNDQxMWRjN2FlNjJlMzVjZGNkZjM1ODdjMjc4NmZjY2ZjYWYzZWRmZTExNDI3OWY2ZDgifQ==');
        return view('Push::users.login');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function doLogin(Request $requests)
    {
        $is_login = PushUser::where('username', $requests->username)->first();
        // dd($requests);
        if($is_login && Crypt::decryptString($is_login->password) == $requests->password) {
            $catename = Category::find($is_login->category_id)->category;
            session(['is_login'=> true]);
            session(['user'=> ['username'=>$is_login->username,'roles'=>3, 'id'=>$is_login->id, 'category'=>$catename]]);

            return redirect(Route('push.notification.index'));
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
        return redirect(route('push.login'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $user_id = session('user')['id'];
        $category_id = PushUser::find($user_id)->category_id;
        $careers = Career::pluck('name','id');
        $facultys = Faculty::pluck('name','id');
        $area_hospital = Province::pluck('province_name','province_name');
        $memnotin = UserCategory::where('category_id', $category_id)->groupBy('member_id')->pluck('member_id')->toArray();
        // dd($memnotin);
        $members = Member::where('category_id', $category_id)
	        ->where('status', 1)
	        ->whereNotIn('id', $memnotin)
	        ->pluck('code','id');
        return view('Push::users.create', compact(['careers','facultys','members','area_hospital']));
    }
    public function ajax_search(Request $request)
    {
        $user_id = session('user')['id'];
        $category_id = PushUser::find($user_id)->category_id;
        $member_s = $request->member;
        $lists = Member::where('category_id', $category_id)->where('code','LIKE', $member_s."%")->get();
        echo json_encode($lists);die();
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
            
            return redirect(route('push.users.create'))
                        ->withErrors($validator)
                        ->withInput();
        }
        $user_id = session('user')['id'];
        $category_id = PushUser::find($user_id)->category_id;

        $data = $requests->all();
        unset($data['_token']);
        unset($data['_method']);
        unset($data['password_conf']);
        unset($data['member_id']);
        // unset($data['avatar']);
        $data['password'] = Crypt::encryptString($data['password']);
        $data['token'] = Crypt::encryptString(date('Y-m-d'));
        $data['roles'] = 2;
        
        $rs = User::insertGetId($data);
        if($rs) {
            if($requests->avatar){
                $path = '/storage/app/'.@$requests->file('avatar')->store('users');
                $users = User::find($rs);
                $users->avatar = $path;
                $users->save();
            }
            $cate = new UserCategory;
            $cate->category_id = $category_id;
            $cate->member_id = $requests->member_id;
            $cate->user_id = $rs;
            $cate->status = 1;
            $cate->save();
            return redirect(route('push.user.import'));
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
        $user = User::with(['career','faculty','association.category','association.member','association'])->where('id', $id)->first();
        $user->password = Crypt::decryptString($user->password);
        $user->token = Crypt::encryptString(date('Y-m-d'));
        $careers = Career::pluck('name','id');
        $facultys = Faculty::pluck('name','id');
        $area_hospital = Province::pluck('province_name','province_name');
        $categories = Category::pluck('category','id');
        $association = [];
        if($user->association) {
            foreach ($user->association as $key => $value) {
                $association[] = $value->category_id;
            }
            $associ= json_encode($association);
        }
        return view('Push::users.edit', compact('user','careers','facultys','categories','associ','area_hospital'));
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
        $user_id = session('user')['id'];
        $category_id = PushUser::find($user_id)->category_id;
        $validator = Validator::make(
            $requests->all(), 
            [
                // 'email' => 'required|email',
                'nickname' => 'required',
                'password' => 'required',
            ],
            [
                // 'email.required' => 'メールアドレスが間違っています。',
                // 'email.email' => 'メールアドレスが間違っています。',
                'nickname.required' => 'ニックネームが間違っています。',
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
        unset($data['password_conf']);
        unset($data['association_id']);
        unset($data['member_code']);
        unset($data['avatar']);
        unset($data['association_id']);
        unset($data['member_code']);
        $data['password'] = Crypt::encryptString($data['password']);        
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
            $user_category = UserCategory::where('user_id', $id)->where('category_id', $category_id);
            // dd($user_category->get());
            $member_code =$requests->member;
            $checkmember = Member::where('category_id', $category_id)
            ->where('code', $member_code)->first();
            if($checkmember) {
                $member_id = $checkmember->id;
            }else{
                $member_id = Member::insertGetId(
                    [
                        'code' => $member_code,
                        'category_id' => $category_id,
                        'status' => 1,
                    ]
                );
            }
            
            $user_category->update([
                'status'=>1, 
                'approve_date'=>date('Y-m-d H:i:s'),
                'member_id'=>$member_id,
            ]);
            return redirect(route('push.user.list_confirm'));
        }else{
            return back();
        }
    }
    public function listConfirm(Request $request){
        $user_id = session('user')['id'];
        $category_id = PushUser::find($user_id)->category_id;
        $members = Member::where('category_id', $category_id)->where('status', 1)->pluck('code', 'id');
        // echo $category_id;die();
        // $users = User::with(['association'=>function($qr)use($category_id){
        //                 $qr->with(['member'])->where('category_id', $category_id)->where('status', 0);
        //             }])
        //             ->where(function($qr)use($category_id){
        //                 $qr->where('users.status', 0)
        //                 ->whereHas('association', function($qr1)use($category_id){
        //                     $qr1->where('category_id', $category_id);
        //                 });
        //             })
        //             ->orWhereHas('association', function($qr)use($category_id){
        //                 $qr->where('category_id', $category_id)->where('status', 0);
        //             })
        //             ->select('users.*','user_categories.id as cate_id','user_categories.status as cate_status', 'user_categories.request_date as order')
                    
        //             ->join('user_categories', 'users.id', '=', 'user_categories.user_id')
        //             ->orderBy('request_date', 'DESC')
        //             ->paginate(20);
        $careers = Career::pluck('name','id');
        $facultys = Faculty::pluck('name','id');
        $area_hospital = Province::pluck('province_name','province_name');            
        $usercates = UserCategory::with(['member', 'user'])
                    ->where('category_id', $category_id)
                    ->where('status', 0)
                    ->orWhere(function($qqr)use($category_id){
                        $qqr->where('status', 1)->where('category_id', $category_id)
                            ->WhereHas('user', function($qr){
                                $qr->where('status', 0);
                            });
                    })
                    ->orderBy('request_date', 'DESC')
                    ->paginate(20);
        // dd($usercates);
        return view('Push::users.confirm', compact('usercates', 'members','careers','facultys','area_hospital'));
    }

    public function listRefuse(Request $request){
        $user_id = session('user')['id'];
        $category_id = PushUser::find($user_id)->category_id;
        $members = Member::where('category_id', $category_id)->where('status', 1)->pluck('code', 'id');
        $usercates = UserCategory::with(['member', 'user'])
                    ->where('category_id', $category_id)
                    ->where('status', 2)
                    // ->where(function($qrx)use($category_id){
                    //     $qrx->where('status', 0)
                    //     ->orWhereHas('user', function($qr)use($category_id){
                    //         $qr->where('status', 0);
                    //     });
                    // })
                    ->orderBy('request_date', 'DESC')
                    ->paginate(20);
        // dd($users);
        return view('Push::users.refuse', compact('usercates', 'members'));
    }
    public function index(Request $request){

        $user_id = session('user')['id'];
        $category_id = PushUser::find($user_id)->category_id;
    	$users = User::with(['association'=>function($qr)use($category_id){
                        $qr->where('category_id', $category_id)->where('type', 0);
                    }])
                    ->select('users.*','user_categories.id as cate_id', 'user_categories.request_date as order')
                    ->whereHas('association', function($qr)use($category_id){
                        $qr->where('category_id', $category_id)->where('type', 0);
                    })
                    ->join('user_categories', 'users.id', '=', 'user_categories.user_id')
                    ->orderBy('request_date', 'DESC')
                    ->paginate(20);
        // dd($users[0]);
        return view('Push::users.index', compact('users'));
    }
    public function import(Request $request){
        $user_id = session('user')['id'];
        $category_id = PushUser::find($user_id)->category_id;
        $members = Member::with(['associationu.user.career','association']);
        $members = $members->whereHas('association', function($qr)use($category_id){
            $qr->where('category_id', $category_id);
        });
        $members = $members->where('status', 1)->orderBy('code', 'ASC')->paginate(20);
        $categories = Category::pluck('category','id');
        $user_total = UserCategory::where('category_id', $category_id)
            ->whereHas('user', function($qr){
                $qr->where('status', 1);
            })
            ->whereHas('member', function($qr){
                $qr->where('status', 1);
            })
            ->count();
        $careers = Career::pluck('name','id');
        $facultys = Faculty::pluck('name','id');
        $area_hospital = Province::pluck('province_name','province_name');
        // dd($members);
        return view('Push::users.import', compact(['members','categories', 'user_total','careers','facultys','area_hospital']));
    }
    public function postImport(Request $request)
    {
        $user_id = session('user')['id'];
        $category_id = PushUser::find($user_id)->category_id;
        $categories = Category::select('id', 'category')->where('id', $category_id)->pluck('category', 'id')->toArray();
         // dd(array_search('1041',$categories));
        $file = $request->member->path();
        $dt_import = \Excel::selectSheetsByIndex(0)->load($file)->get();
        for($ii = 0; $ii < $dt_import->count(); $ii++) {
            $results = $dt_import->get($ii);
            $values = $results->values()->toArray();
            $id=array_search($values[0],$categories);
            // dd($values[1]);
            if($id && !empty($values[1])) {
                $ar = [
                    'category_id' => $id,
                    'code' => $values[1]
                ];
                $dt = [
                    'status' => 1
                ];
                Member::updateOrCreate($ar,$dt);
            } 
        }  
        return redirect(route('push.user.import'));
    }
    public function approve(Request $request){
        $user = User::find($request->id);
        if(!$user) return redirect(route('push.user.list_confirm'));
        $user_id = session('user')['id'];
        $category_id = PushUser::find($user_id)->category_id;
        // dd($request->id);
        $check_send = UserCategory::where('user_id', $request->id)->where('status', 1)->get();

        $user_category = UserCategory::where('user_id', $request->id)->where('category_id', $category_id);
        $member = $user_category->first()->member_id;
        $member_code = Member::find($member)->code;
        // dd($member_code);
        Member::find($member)->update(['status'=>1]);
        $user_category->update(['status'=>1, 'approve_date'=>date('Y-m-d H:i:s')]);
        if(!count($check_send)){
            $user = User::find($request->id);
            // dd($check_send->id);
            $mail = $user->email;
            $authentication_code = generateRandomCode(6);
            VeryMail::updateOrCreate(['email'=>$mail],['code'=>$authentication_code]);
            $rsm = Mail::send('users.mailregis', ['text' => $authentication_code], function ($m) use ($mail) {
                    $m->to($mail, 'MEDICAL MASTERS運営事務局')->subject('MEDICAL MASTERS運営事務局');
                });
        }
        $user = User::find($request->id);
        $datamail['nickname'] = $user->nickname;
        $datamail['hh'] = Category::find($category_id)->category;
        $rsm1 = Mail::send('users.mailconfirm', ['data' => $datamail], function ($m) use ($user, $member_code) {
            $m->to($user->email, 'MEDICAL MASTERS運営事務局')->subject('MEDICAL MASTERS運営事務局');
        });

        return redirect(route('push.user.list_confirm'));
    }
    public function editapprove(Request $request){
        $user = User::find($request->id);
        if(!$user) return redirect(route('push.user.list_confirm'));
        $user_id = session('user')['id'];
        $category_id = PushUser::find($user_id)->category_id;

        $check_send = UserCategory::where('user_id', $request->id)->where('status', 1)->get();

        $user_category = UserCategory::where('user_id', $request->id)->where('category_id', $category_id);
        // $member = $user_category->first()->member_id;
        // Member::find($member)->update(['status'=>1]);
        $member_code =$request->member;
        $checkmember = Member::where('category_id', $category_id)
        ->where('code', $member_code)->first();
        if($checkmember) {
            $member_id = $checkmember->id;
        }else{
            $member_id = Member::insertGetId(
                [
                    'code' => $member_code,
                    'category_id' => $category_id,
                    'status' => 1,
                ]
            );
        }
        
        $user_category->update([
            'status'=>1, 
            'approve_date'=>date('Y-m-d H:i:s'),
            'member_id'=>$member_id,
        ]);
        if(!count($check_send)){
            $user = User::find($request->id);
            // dd($check_send->id);
            $mail = $user->email;
            $authentication_code = generateRandomCode(6);
            VeryMail::updateOrCreate(['email'=>$mail],['code'=>$authentication_code]);
            $rsm = Mail::send('users.mailregis', ['text' => $authentication_code], function ($m) use ($mail) {
                    $m->to($mail, 'MEDICAL MASTERS運営事務局')->subject('MEDICAL MASTERS運営事務局');
                });
        }
        $user = User::find($request->id);
        $datamail['nickname'] = $user->nickname;
        $datamail['hh'] = Category::find($category_id)->category;
        $rsm1 = Mail::send('users.mailconfirm', ['data' => $datamail], function ($m) use ($user, $member_code) {
            $m->to($user->email, 'MEDICAL MASTERS運営事務局')->subject('MEDICAL MASTERS運営事務局');
        });

        return redirect(route('push.user.list_confirm'));
    }
    public function resendAuth(Request $request){
        $user = User::find($request->id);
        if(!$user) return redirect(route('push.user.list_confirm'));
        $user_id = session('user')['id'];
        $category_id = PushUser::find($user_id)->category_id;

        $check_send = UserCategory::where('user_id', $request->id)->where('status', 1)->get();

        if(!count($check_send)){
            $user = User::find($request->id);
            // dd($check_send->id);
            $mail = $user->email;
            $authentication_code = generateRandomCode(6);
            VeryMail::updateOrCreate(['email'=>$mail],['code'=>$authentication_code]);
            $rsm = Mail::send('users.mailregis', ['text' => $authentication_code], function ($m) use ($mail) {
                    $m->to($mail, 'MEDICAL MASTERS運営事務局')->subject('MEDICAL MASTERS運営事務局');
                });
        }

        return redirect(route('push.user.list_confirm'));
    }
    public function refuse(Request $request){
        $user = User::find($request->id);
        if(!$user) return redirect(route('push.user.list_confirm'));
        $user_id = session('user')['id'];
        $category_id = PushUser::find($user_id)->category_id;

        UserCategory::where('user_id', $request->id)->where('category_id', $category_id)->update(['status'=>2, 'refuse_date'=>date('Y-m-d H:i:s')]);
        $member_id = UserCategory::where('user_id', $request->id)->where('category_id', $category_id)->first()->member_id;

        $member_code = Member::find($member_id)->code;
        $user = User::find($request->id);
        $datamail['nickname'] = $user->nickname;
        $datamail['hh'] = Category::find($category_id)->category;
        $rsm1 = Mail::send('users.mailreject', ['data' => $datamail], function ($m) use ($user, $member_code) {
            $m->to($user->email, '【MEDICAL MASTERS】からご登録に関するご連絡')->subject('【MEDICAL MASTERS】からご登録に関するご連絡');
        });
        //学会の認証に関して
        return redirect(route('push.user.list_confirm'));
    }

    public function deleterequest(Request $request, $id){
        $user = User::find($id);
        if(!$user) return redirect(route('push.user.list_confirm'));
        $user_id = session('user')['id'];
        $category_id = PushUser::find($user_id)->category_id;
        UserCategory::where('user_id', $id)->where('category_id', $category_id)->delete();
        //check delete token
        $user_stt = User::find($id);
        if($user_stt->status == 0) {
             $status = 0;
        }else{
            $check = UserCategory::where('user_id', $id)->pluck('status')->toArray();
            $status = 0;
            if(in_array("1", $check)) $status = 1; // có thể login được
            else{
                if(in_array("0", $check)) $status = 0; // đợi nhập authen
                else $status = 0;//2 buột đăng ký lại
            }
        }
        if($status == 0) {
            User::destroy($id);
            Device::where("user_id", $id)->delete();
            UserCategory::where('user_id', $id)->delete();
        }
        //学会の認証に関して
        return redirect(route('push.user.list_confirm'));
    }

    public function cron_mail_request_user(){
        $weeksub1 = Carbon::now()->subWeeks(1)->format('Y-m-d H:i:s');
        $users = User::with(['association'=>function($qr)use($category_id){
            $qr->where('category_id', $category_id)->where('type', 0);
        }])
        ->whereHas('association', function($qr)use($category_id, $weeksub1){
            $qr->where('category_id', $category_id)->where('type', 0)
            ->where('created_at', '<=', $weeksub1)->where('status', 0);
        })
        ->get();
        $str = "";
        foreach ($users as $key => $value) {
            $str .= "OO学会のOO　さん　OOOO@OOOO　の対応が1週間経ちました";
        }
    }
    public function export(Request $request){
        $user_id = session('user')['id'];
        $category_id = PushUser::find($user_id)->category_id;
        $members = Member::with(['associationu.user.career','association']);
        $members = $members->whereHas('association', function($qr)use($category_id){
            $qr->where('category_id', $category_id);
        });
        $members = $members->where('status', 1)->orderBy('id', 'DESC')->get();
        $titles = ['hh','code'];
        foreach ($members as $key => $value) {
            $datas[$key]['hh'] = @$value->association->category;
            $datas[$key]['code'] = @$value->code;
        }
        $this->exportF($titles, $datas, 'member_list_'.date('ymdHis').'.csv');
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