<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Hall;
use DB;
use Illuminate\Support\Facades\Storage;

class HallController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($seminar_id)
    {
        if(!$seminar_id) return back();
        $halls = Hall::where('id', '>', 0)
                ->where('seminar_id', $seminar_id)
                ->orderBy('nabi', 'ASC')
                ->paginate(2000);
        
        return view('halls.index',compact(['halls']));
    }

     /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function ajaxsoft(Request $request)
    {
        //
        $id = $request->id;
        $soft = $request->soft;
        $id = $id."cc";
        $id = str_replace('_cc', '', $id);
        $id_ar = explode('_', $id);
        // echo json_encode($id_ar);
        $nabi = 1;
        foreach ($id_ar as $key => $value) {
            Hall::where('id', $value)->update([
                'nabi' => $nabi
            ]);
            $nabi++;
        }
        echo '1';die();
    }
}
