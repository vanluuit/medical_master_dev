<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Event;
use App\EventDetail;
use App\UserEvent;
use App\Seminar;
use App\CategoryEvent;
use App\Category;
use App\ThemeEvent;
use App\ViewEvent;

use PDF;

use DB;
use Validator;
use PHPExcel;

class TestController extends Controller
{
    public function metavideo()
    {
        $file = '/var/www/html/storage/app/TVpro/videos/ff85780b75e0f26bbae9a2445e22d733/playlist.m3u8';
        $time = exec('/usr/local/bin/bin/ffmpeg -i '.$file.' 2>&1 | grep "Duration" | cut -d " " -f 4 | sed s/,//');

        print_r($time);

       
        die();
    }
    public function postvideo()
    {
        return view('test.postvideo');
    }
    public function postvideo1(Request $request)
    {
    	set_time_limit(500000);
        dd($request);
    }
}
