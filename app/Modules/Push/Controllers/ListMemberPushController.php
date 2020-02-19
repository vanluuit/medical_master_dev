<?php

namespace App\Modules\Push\Controllers;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\ListMemberPush;
use App\MemberListMemberPush;
use App\UserCategory;
use App\PushUser;
use App\Member;


use App\User;
use App\Device;

class ListMemberPushController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $lists = ListMemberPush::with(['memberList'])->paginate(20);
        return view('Push::list_member_push.index', compact('lists'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $user_id = session('user')['id'];
        $category_id = PushUser::find($user_id)->category_id;
        $total = $user_total = 0;
        $data = [];
        if ($request->isMethod('post')) {
            $name = $request->name;
            $file = $request->member->path();
            $dt_import = \Excel::selectSheetsByIndex(0)->load($file)->get();
            $memberlist = Member::where('category_id', $category_id)->pluck('code')->toArray();
            $menber_ids = UserCategory::where('category_id', $category_id)->pluck('member_id')->toArray();
            $memberuse = Member::whereIn('id',  $menber_ids)->pluck('code')->toArray();
            for($ii = 0; $ii < $dt_import->count(); $ii++) {
                $results = $dt_import->get($ii);
                $values = $results->values()->toArray();
                if($values[0] != "") $total++;
                if(in_array($values[0], $memberuse)) {
                    $user_total++;
                    $data[] = $values[0];
                }
            }  
            return view('Push::list_member_push.postcreate', compact(['total', 'user_total', 'method', 'data', 'name']));
        }
        return view('Push::list_member_push.create', compact(['total', 'user_total', 'data', 'name']));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $user_id = session('user')['id'];
        $category_id = PushUser::find($user_id)->category_id;
        $data = [
            'name'=> $request->name,
            'category_id'=> $category_id,
        ];
        
        $rs = ListMemberPush::insertGetId($data);
        if($rs) {
            if($request->member_id) {
                $memberlist = Member::where('category_id', $category_id)->pluck('code', 'id')->toArray();
                foreach ($request->member_id as $key => $member_code) {
                    $member_id=array_search($member_code,$memberlist);
                    $datam = [
                        'list_member_id' => $rs,
                        'category_id' => $category_id,
                        'member_id' => $member_id,
                    ];
                    MemberListMemberPush::insertGetId($datam);
                }
            }
            return redirect(route('push.listmemberpushs.index'));
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
    public function edit(Request $request, $id)
    {
        //
        $user_id = session('user')['id'];
        $category_id = PushUser::find($user_id)->category_id;
        $total = $user_total = 0;
        $data = [];
        $list = ListMemberPush::find($id);
        if ($request->isMethod('post')) {
            $name = $request->name;
            $file = $request->member->path();
            $dt_import = \Excel::selectSheetsByIndex(0)->load($file)->get();
            $memberlist = Member::where('category_id', $category_id)->pluck('code')->toArray();
            $menber_ids = UserCategory::where('category_id', $category_id)->pluck('member_id')->toArray();
            $memberuse = Member::whereIn('id',  $menber_ids)->pluck('code')->toArray();
            for($ii = 0; $ii < $dt_import->count(); $ii++) {
                $results = $dt_import->get($ii);
                $values = $results->values()->toArray();
                if($values[0] != "") $total++;
                if(in_array($values[0], $memberuse)) {
                    $user_total++;
                    $data[] = $values[0];
                }
            }  
            return view('Push::list_member_push.postedit', compact(['total', 'user_total', 'method', 'data', 'name', 'list']));
        }
        return view('Push::list_member_push.edit',compact(['list']));
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
        $user_id = session('user')['id'];
        $category_id = PushUser::find($user_id)->category_id;
        $listmem = ListMemberPush::find($id);
        $listmem->name = $request->name;
        $listmem->save();
        MemberListMemberPush::where('list_member_id', $id)->delete();
        if($request->member_id) {
            $memberlist = Member::where('category_id', $category_id)->pluck('code', 'id')->toArray();
            foreach ($request->member_id as $key => $member_code) {
                $member_id=array_search($member_code,$memberlist);
                $datam = [
                    'list_member_id' => $id,
                    'category_id' => $category_id,
                    'member_id' => $member_id,
                ];
                MemberListMemberPush::insertGetId($datam);
            }
        }
        return redirect(route('push.listmemberpushs.index'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        ListMemberPush::destroy($id);
        MemberListMemberPush::where('list_member_id', $id)->delete();
        return redirect(route('push.listmemberpushs.index'));
    }

}
