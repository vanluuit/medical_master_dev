<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use LRedis;
use SocketIO;




class SocketController extends Controller
{
    //
    public function __construct(){
    	$this->middleware('guest');
    }
    public function index(){
    	return view('socket');
    }
    public function writeMessage(){
    	return view('writemessage');
    }
    public function sendMessage(Request $request){
    	// // $redis = LRedis::connection();
    	// $redis = LRedis::connection();
    	// $redis->publish('message', $request->message);
    	$client = new SocketIO('visoftech.com', 9001);
        $client->setQueryParams([
            'token' => 'edihsudshuz',
            'id' => '8780',
            'cid' => '344',
            'cmp' => 2339
        ]);

        $success = $client->emit('message', $request->message);
    	return redirect(route('socket.writeMessage'));
    }
}
