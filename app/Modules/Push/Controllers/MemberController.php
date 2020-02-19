<?php

namespace App\Modules\Push\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Member;
use App\Device;
use App\User;
use App\Category;
use App\UserCategory;
use App\PushUser;

class MemberController extends Controller
{
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //
        $members = Member::with(['users','association']);
        if($request->category_id){
            $members = $members->whereHas('association', function($qr)use($request){
                $qr->where('category_id', $request->category_id);
            });
        }
        $members = $members->where('status', 1)->orderBy('created_at', 'DESC')->paginate(20);
        $categories = Category::pluck('category','id');
        return view('members.index',compact(['members','categories']));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        return view('Push::members.create');
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
        $user_id = session('user')['id'];
        $category_id = PushUser::find($user_id)->category_id;
        $data = $request->all();
        $data['status'] = 1;
        $data['category_id'] = $category_id;
        unset($data['_token']);
        $rs = Member::insertGetId($data);
        if($rs) {
            return redirect(route('push.user.import'));
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
        $member = Member::find($id);
        $categories = Category::pluck('category','id');
        return view('members.edit', compact(['member','categories']));
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
        $rs = Member::find($id)->update($data);
        if($rs) {
            return redirect(route('push.user.import'));
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
        // dd($id);
        Member::destroy($id);
        UserCategory::where('member_id', $id)->delete();
        $users = User::with(['association'])->where('roles', 2)->get();
        if(count($users)) {
            foreach ($users as $key => $value) {
                if(!count($value->association)) {
                    User::where('id', $value->id)->update(['token'=>""]);
                    Device::where('user_id', $value->id)->delete();
                }
            }
        }
        return redirect(route('push.user.import'));
    }

    public function import(Request $request)
    {
        //
        // $file = $request->file('member');
        // $file = $request->filecsv->path();
        $categories = Category::select('id', 'category')->pluck('category', 'id')->toArray();
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
        return redirect(route('members.index'));
    }
    
    public function ajaxcode(Request $request)
    {
        //
        $members = Member::where('category_id', $request->category_id)->pluck('code');
        echo json_encode($members);die();
    }
}
