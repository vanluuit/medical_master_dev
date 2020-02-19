<?php
use App\User;
use App\CommentDiscussion;
use Carbon\Carbon;

function checkexit($url) {
	$headers = @get_headers($url);
	if(strpos($headers[0],'404') === false)
	{
  	return true;
	}
	else
	{
  	return false;
	}
}
function get_client_ip() {
  $ipaddress = '';
  if (isset($_SERVER['HTTP_CLIENT_IP']))
      $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
  else if(isset($_SERVER['HTTP_X_FORWARDED_FOR']))
      $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
  else if(isset($_SERVER['HTTP_X_FORWARDED']))
      $ipaddress = $_SERVER['HTTP_X_FORWARDED'];
  else if(isset($_SERVER['HTTP_FORWARDED_FOR']))
      $ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
  else if(isset($_SERVER['HTTP_FORWARDED']))
      $ipaddress = $_SERVER['HTTP_FORWARDED'];
  else if(isset($_SERVER['REMOTE_ADDR']))
      $ipaddress = $_SERVER['REMOTE_ADDR'];
  else
      $ipaddress = 'UNKNOWN';
  return $ipaddress;
}
function array_mounth($day) {
  $ar = [];
  for ($i=1; $i <=$day ; $i++) { 
    ($i<=9) ? $a='0'.$i : $a=''.$i;
    $ar[]=$a;
  }
  return $ar;
}

function generateRandomString($length = 10) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}
function sex(){
  return ['0'=>'男性', '1'=>'女性'];
}

function generateRandomCode($length = 10) {
    $characters = '0123456789';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}
function get_user_id($request){
  $token = $request->header('Authorization');
  $token = str_replace('bearer ', '', $token);
  $token = str_replace('bearer', '', $token);
  $token = str_replace(' bearer ', '', $token);
  $user = User::where('token', $token)->first();
  return $user->id;
}

function get_day_text($day){
  $textar = ['0'=>'日','1'=>'月','2'=>'火','3'=>'水','4'=>'木','5'=>'金','6'=>'土'];
  return $textar[date('w', strtotime($day))];
}

function get_list_report(){
    $comments = CommentDiscussion::withCount(['count_report']);
    $comments = $comments->whereHas('count_report')->orderBy('created_at', 'DESC');
    $comments = $comments->paginate(20);
    return $comments;
}

function notification($token, $title)
{
  $fcmUrl = 'https://fcm.googleapis.com/fcm/send';
  $token=$token;

  $notification = [
      'title' => $title,
      'sound' => true,
  ];
  
  $extraNotificationData = ["message" => $notification,"moredata" =>'dd'];

  $fcmNotification = [
      //'registration_ids' => $tokenList, //multple token array
      'to'        => $token, //single token
      'notification' => $notification,
      'data' => $extraNotificationData
  ];

  $headers = [
      'Authorization: key=AIzaSyAxkvgcHO56Hhq1eIyQRtjFERwXPOD6hPA',
      'Content-Type: application/json'
  ];


  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL,$fcmUrl);
  curl_setopt($ch, CURLOPT_POST, true);
  curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
  curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fcmNotification));
  $result = curl_exec($ch);
  curl_close($ch);

  return true;
}
function notification_push($token, $title, $data, $body="")
{
  $fcmUrl = 'https://fcm.googleapis.com/fcm/send';
  $token=$token;
  // if($body=="") {
  //   $body=$title;
  // }
  // $body="";

  $notification = [
      'title' => $title,
      'body' => $body,
      'sound' => true,
  ];
  
  // $extraNotificationData = ["message" => $data,"moredata" =>'dd'];

  $fcmNotification = [
      //'registration_ids' => $tokenList, //multple token array
      'to'        => $token, //single token
      'notification' => $notification,
      'data' => $data
  ];

  $headers = [
      'Authorization: key=AIzaSyAxkvgcHO56Hhq1eIyQRtjFERwXPOD6hPA',
      'Content-Type: application/json'
  ];


  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL,$fcmUrl);
  curl_setopt($ch, CURLOPT_POST, true);
  curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
  curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fcmNotification));
  $result = curl_exec($ch);
  curl_close($ch);

  return true;
}
function time_convert($s=0){
  $h = floor($s/360);
  $s_s = $s%360;
  $i = floor($s_s/60);
  $s = $s_s%60;
  $str = '';
  if($s > 0) $str = $s.'s'.$str;
  if($i > 0) $str = $i.'m'.$str;
  if($h > 0) $str = $h.'h'.$str;
  return $str;
}
function rating_color($number){
  $color = [
    1=>'#ff0000',
    2=>'#1e00ff',
    3=>'#f7962d'
  ];
  return $color[$number];
}
function covert_total_time($str){
  $time = explode('.', $str)[0];
  $t_ar = explode(':', $time);
  $h = (int)$t_ar[0];
  $i = (int)$t_ar[1];
  $s = $t_ar[2];
  $ii = $h*60 + $i;
  if($ii<10) $ii='0'.$ii;
  return $ii.':'.$s;
}
function timeSelect(){
  return [
    "00:00" => "00:00",
    "01:00" => "01:00",
    "02:00" => "02:00",
    "03:00" => "03:00",
    "04:00" => "04:00",
    "05:00" => "05:00",
    "06:00" => "06:00",
    "07:00" => "07:00",
    "08:00" => "08:00",
    "09:00" => "09:00",
    "10:00" => "10:00",
    "11:00" => "11:00",
    "12:00" => "12:00",
    "13:00" => "13:00",
    "14:00" => "14:00",
    "15:00" => "15:00",
    "16:00" => "16:00",
    "17:00" => "17:00",
    "18:00" => "18:00",
    "19:00" => "19:00",
    "20:00" => "20:00",
    "21:00" => "21:00",
    "22:00" => "22:00",
    "23:00" => "23:00",
    // "24:00" => "24:00",
  ];
}

function dayNow(){
  return Carbon::now()->format('Y-m-d');
}
function NowT($format){
  return Carbon::now()->format($format);
}
function timeSel(){
  $rs = [];
  for ($i=0; $i <24; $i++) { 
    $j = $i;
    if($i <10 ) $j = '0'.$i;
    $rs[$j] = $j;
  }
  return $rs;
}
function minuteSel(){
  $rs = [];
  for ($i=0; $i < 60 ; $i++) { 
    $j = $i;
    if($i <10 ) $j = '0'.$i;
    $rs[$j] = $j;
  }
  return $rs;
}