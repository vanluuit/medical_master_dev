<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\CategoryEvent;

use App\User;
use App\Device;
use App\Event;

class CategoryEventController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $requests)
    {
        //
        $categories = CategoryEvent::where('seminar_id', $requests->seminar_id)->orderBy('name_search', 'ASC')->paginate(20);
        return view('categoryevents.index', compact('categories'));
    }
    public function namesearch(Request $requests)
    {
        //
        $categories = CategoryEvent::where('seminar_id', $requests->seminar_id)
        ->groupBy('name_search')
        ->orderBy('name_search', 'ASC')
        ->paginate(20);
        return view('categoryevents.namesearch', compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        return view('categoryevents.create');
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
        $rs = CategoryEvent::insertGetId($data);
        if($rs) {
            return redirect(route('categoryevents.index'));
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
        $category = CategoryEvent::find($id);
        return view('categoryevents.edit',compact(['category']));
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
        $category = CategoryEvent::find($id);
        $category->name = $request->name;
        $category->name_search = $request->name_search;
        $category->save();
        return redirect(route('categoryevents.index'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Event::where('category_event_id', $id)->delete();
        CategoryEvent::destroy($id);
        return redirect(route('categoryevents.index'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function setcolor(Request $request)
    {
        // $user = User::find($id);
        $category = CategoryEvent::find($request->id);
        $color = '#'.$request->color;
        CategoryEvent::where('name_search', $category->name_search)->update(['color'=>$color]);
        echo 1; die();
    }
}
