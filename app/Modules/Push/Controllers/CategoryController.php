<?php

namespace App\Modules\Push\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Category;

use App\PushUser;
use App\Device;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $user_id = session('user')['id'];
        $user = PushUser::find($user_id);
        // dd($user->category_id);
        $categories = Category::where('id', $user->category_id)->get();
        return view('Push::associations.index', compact('categories'));
    }
}
