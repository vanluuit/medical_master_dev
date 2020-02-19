<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Member;
use App\Device;
use App\User;
use App\Category;
use App\UserCategory;

class MemberController extends Controller
{
    // api
    
    public function api_check(Request $request)
    {
        if($request->association_id || $request->code) {
            $data["statusCode"] = 1;
            $data["message"] = "parameters do not exist";
        }
        $check = Member::where('category_id', $request->association_id)->where('code', $request->code)->where('status', 1)->count(); 
        if($check) {
            $data["statusCode"] = 0;
            $data["message"] = "";
            $data['data']['is_check'] = true;  
        }else{
            $data["statusCode"] = 1;
            $data["message"] = "Not a member of the association";
            $data['data']['is_check'] = false; 
        }
        
        
        return response()->json($data);
    }
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
        $categories = Category::pluck('category','id');
        return view('members.create', compact(['categories']));
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
        $data['status'] = 1;
        unset($data['_token']);
        $rs = Member::insertGetId($data);
        if($rs) {
            return redirect(route('members.index'));
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
            return redirect(route('members.index'));
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
        return redirect(route('members.index'));
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
