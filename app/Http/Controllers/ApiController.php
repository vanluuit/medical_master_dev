<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Api;

class ApiController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $apis = Api::paginate(20);
        return view('apis.index',compact('apis'));
    }

    public function create()
    {
        $string = md5(date('Y-m-d H:i:s').generateRandomString(8)).generateRandomString(10);
        $api = new Api;
        $api->api_key = $string;
        $api->save();
        return redirect(route('api_key'));
    }

    public function destroy(Request $requests)
    {
        Api::destroy($requests->id);
        return redirect(route('api_key'));
    }
}
