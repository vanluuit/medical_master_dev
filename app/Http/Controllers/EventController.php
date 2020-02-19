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
use App\Hall;

use PDF;

use DB;
use Validator;
use PHPExcel;

class EventController extends Controller
{

    public function api_list_by_day(Request $request)
    {
        $user_id = get_user_id($request);
        $events = [];
        $select = ['id', 'seminar_id', 'category_event_id','theme_event_id', 'topic_number', 'name_basis', 'hall', 'floor', 'room', 'year','month','day','thday','start_time_view', 'end_time_view','start_time', 'end_time', 'preside', 'name', 'content'];
        // return response()->json($user_id);
        $events = Event::select($select)
            ->with(['theme'=>function($qr){
                $qr->with(['events'=>function($qr1){
                    $qr1->select(['id', 'seminar_id', 'category_event_id','theme_event_id', 'name']);
                }]);
            }])
            ->withCount(['count_mypage', 'is_mypage'=>function($qr1)use($user_id){
                $qr1->where('user_id', $user_id);
            }])
            ->where('seminar_id', $request->seminar_id)
            ->where('start_time','>=',$request->date.' 00:00:00')
            ->where('start_time','<=',$request->date.' 23:59:59')
            ->paginate(10);
        if($events) {
            $data["statusCode"] = 0;
            $data["message"] = "";
            $data['data'] = $events;  
        }else{
            $data["statusCode"] = 0;
            $data["message"] = "";
            $data['data'] = $events;
        }
        return response()->json($data);
    }
    public function api_list_ranking(Request $request)
    {
        // $eventsxx=[];

        $user_id = get_user_id($request);
        $events = Event::select( 'id', 'seminar_id', 'category_event_id','theme_event_id', 'name','start_time')
            ->with(['category', 'theme'])
            ->withCount(['count_mypage', 'is_mypage'=>function($qr1)use($user_id){
                $qr1->where('user_id', $user_id);
            },'count_view'=>function($qr2){
                $qr2->where('created_at', '<=', date('Y-m-d H:i:s'))
                    ->where('created_at', '>=', date('Y-m-d H:i:s', strtotime('-24 hours')));
            }])
            ->where('seminar_id', $request->seminar_id);
            $events = $events->orderBy('count_view_count', 'DESC')->orderBy('start_time', 'DESC')->paginate(10);

        if($events) {
            $data["statusCode"] = 0;
            $data["message"] = "";
            $data['data'] = $events;  
        }else{
            $data["statusCode"] = 0;
            $data["message"] = "";
            $data['data'] = $events;
        }
        
        
        return response()->json($data);
    }
    public function api_list_by_calendar(Request $request)
    {
        $user_id = get_user_id($request);
        $events = Event::with(['category', 'theme', 'ratings'=>function($qr)use($user_id){
                $qr->where('user_id', $user_id);
            }])
            ->join('halls', 'events.hall_id', '=', 'halls.id')
            ->select( 'events.id', 'events.seminar_id', 'events.category_event_id','theme_event_id', 'events.topic_number', 'events.name_basis','sponsored', 'events.hall', 'events.floor', 'events.room', 'events.thday' ,'start_time', 'events.end_time', 'events.preside', 'halls.nabi','halls.id as place')
            ->withCount(['count_mypage', 'is_mypage'=>function($qr1)use($user_id){
                $qr1->where('user_id', $user_id);
            }])
            ->where('events.seminar_id', $request->seminar_id)
            ->where('start_time','>=',$request->date.' 00:00:00')
            ->where('start_time','<=',$request->date.' 23:59:59')
            ->orderBy('nabi', 'ASC')
            ->orderBy('start_time', 'ASC')
            ->paginate(10000);
            foreach ($events as $key => $value) {

                if(@$value->ratings[0]->rating == 1) @$value->ratings[0]->color = '#ff0000';
                elseif(@$value->ratings[0]->rating == 2) @$value->ratings[0]->color = '#1e00ff';
                else @$value->ratings[0]->color = '#f7962d';
                $cx = @$value->ratings[0];
                unset($value->ratings);
                $value->ratings = $cx;
                $value->date_time = date('Y年m月d日', strtotime($value->start_time)).'('.$value->thday.') '.date('H:i', strtotime($value->start_time)).'-'.date('H:i', strtotime($value->end_time));
            }

        if($events) {
            $data["statusCode"] = 0;
            $data["message"] = "";
            $seminar = Seminar::find($request->seminar_id);
            $time = @json_decode($seminar->time, true)[$request->date];
            $data['data'] = $events->toArray();
            $data['data']['time'] = $time ;

        }else{
            $data["statusCode"] = 0;
            $data["message"] = "";
            $data['data'] = $events;
        }
        
        
        return response()->json($data);
    }

    public function api_list_by_category(Request $request)
    {
        // $eventsxx=[];
        $user_id = get_user_id($request);
        $name_search = CategoryEvent::find($request->category_id)->name_search;
        $events = Event::select( 'id', 'seminar_id', 'category_event_id','theme_event_id', 'topic_number', 'name_basis','sponsored', 'hall', 'floor', 'room', 'year','month','day','thday','start_time_view', 'end_time_view','start_time', 'end_time', 'preside')
            ->with(['category', 'theme'])
            ->withCount(['count_mypage', 'is_mypage'=>function($qr1)use($user_id){
                $qr1->where('user_id', $user_id);
            }])
            ->whereHas('category', function($qr)use($name_search){
                $qr->where('name_search', $name_search);
            })
            ->where('seminar_id', $request->seminar_id);
            if($request->date) {
                $date_ar = json_decode($request->date);
                $events = $events->where(function($qr2)use($date_ar){
                    foreach ($date_ar as $key => $value) {
                        if($key==0){
                            $qr2 = $qr2->where(function($query)use($value){
                                $query->where('start_time','>=',$value.' 00:00:00')
                                    ->where('start_time','<=',$value.' 23:59:59');
                            });
                        }else{
                            $qr2 = $qr2->orWhere(function($query)use($value){
                                $query->where('start_time','>=',$value.' 00:00:00')
                                    ->where('start_time','<=',$value.' 23:59:59');
                            });
                        }
                    }
                });
            }
            $events = $events->orderBy('start_time', 'ASC')->paginate(10000);
            foreach ($events as $key => $value) {
                $value->date_time = date('Y年m月d日', strtotime($value->start_time)).'('.$value->thday.') '.date('H:i', strtotime($value->start_time)).'-'.date('H:i', strtotime($value->end_time));
            }


            $data["statusCode"] = 0;
            $data["message"] = "";
            $data['data'] = $events;  
        
        return response()->json($data);
    }
    public function api_list_by_theme(Request $request)
    {
        // $eventsxx=[];
        $user_id = get_user_id($request);
        $events = Event::select( 'id', 'seminar_id', 'category_event_id','theme_event_id', 'topic_number', 'name_basis','sponsored', 'hall', 'floor', 'room', 'year','month','day','thday','start_time_view', 'end_time_view','start_time', 'end_time', 'preside')
            ->with(['category', 'theme'])
            ->withCount(['count_mypage', 'is_mypage'=>function($qr1)use($user_id){
                $qr1->where('user_id', $user_id);
            }])
            ->whereHas('theme', function($qr)use($request){
                $qr->where('id', $request->theme_id);
            })
            ->where('seminar_id', $request->seminar_id);
            if($request->date) {
                $date_ar = json_decode($request->date);
                $events = $events->where(function($qr2)use($date_ar){
                    foreach ($date_ar as $key => $value) {
                        if($key==0){
                            $qr2 = $qr2->where(function($query)use($value){
                                $query->where('start_time','>=',$value.' 00:00:00')
                                    ->where('start_time','<=',$value.' 23:59:59');
                            });
                        }else{
                            $qr2 = $qr2->orWhere(function($query)use($value){
                                $query->where('start_time','>=',$value.' 00:00:00')
                                    ->where('start_time','<=',$value.' 23:59:59');
                            });
                        }
                    }
                });
            }
            $events = $events->orderBy('start_time', 'ASC')->paginate(10000);
            foreach ($events as $key => $value) {
                $value->date_time = date('Y年m月d日', strtotime($value->start_time)).'('.$value->thday.') '.date('H:i', strtotime($value->start_time)).'-'.date('H:i', strtotime($value->end_time));
            }
            $data["statusCode"] = 0;
            $data["message"] = "";
            $data['data'] = $events;  
        
        return response()->json($data);
    }
    public function api_list_by_name(Request $request)
    {
        $user_id = get_user_id($request);
        $event_detail = EventDetail::find($request->id);
        $events = Event::select( 'id', 'seminar_id', 'category_event_id','theme_event_id', 'topic_number', 'name_basis','sponsored', 'hall', 'floor', 'room', 'year','month','day','thday','start_time_view', 'end_time_view','start_time', 'end_time', 'preside')
            ->with(['category', 'theme'])
            ->withCount(['count_mypage', 'is_mypage'=>function($qr1)use($user_id){
                $qr1->where('user_id', $user_id);
            }])
            ->whereHas('event_detail', function($qr)use($request){
                $qr->where('name', $event_detail->name);
            })
            ->where('seminar_id', $request->seminar_id);
           
            $events = $events->orderBy('start_time', 'ASC')->paginate(10000);
            foreach ($events as $key => $value) {
                $value->date_time = date('Y年m月d日', strtotime($value->start_time)).'('.$value->thday.') '.date('H:i', strtotime($value->start_time)).'-'.date('H:i', strtotime($value->end_time));
            }
            $data["statusCode"] = 0;
            $data["message"] = "";
            $data['data'] = $events;  
        
        return response()->json($data);
    }
    public function event_list_by_contact(Request $request)
    {
        // $eventsxx=[];
        $user_id = get_user_id($request);
        $select = ['id', 'seminar_id', 'category_event_id','theme_event_id', 'topic_number', 'name_basis','sponsored', 'hall', 'floor', 'room', 'year','month','day','thday','start_time_view', 'end_time_view','start_time', 'end_time', 'preside', DB::raw("DATE_FORMAT(start_time, '%Y-%m-%d') as filter_date")];
        $ids1 = Event::with(['category', 'theme'])
            ->withCount(['count_mypage', 'is_mypage'=>function($qr1)use($user_id){
                $qr1->where('user_id', $user_id);
            }])
            ->where(function($qr)use($request){
                $qr->where(function ($query)use($request){
                    $query->where('preside1_first_name', $request->preside_first_name)
                        ->where('preside1_last_name', $request->preside_last_name);
                });
                for ($ii=2; $ii <=8 ; $ii++) { 
                    $qr = $qr->orWhere(function($query)use($request, $ii){
                        $query->where('preside'.$ii.'_first_name', $request->preside_first_name)
                            ->where('preside'.$ii.'_last_name', $request->preside_last_name);
                    });
                }
            })
            ->where('seminar_id', $request->seminar_id)->pluck('id')->toArray();
        $ids2 = EventDetail::where(function($qr)use($request){
                $qr->where(function ($query)use($request){
                    $query->where('member1_first_name', $request->preside_first_name)
                        ->where('member1_last_name', $request->preside_last_name);
                });
                for ($ii=2; $ii <=25 ; $ii++) { 
                    $qr = $qr->orWhere(function($query)use($request, $ii){
                        $query->where('member'.$ii.'_first_name', $request->preside_first_name)
                            ->where('member'.$ii.'_last_name', $request->preside_last_name);
                    });
                }
            })
            ->whereHas('events', function($qr)use($request){
                    $qr->where('seminar_id', $request->seminar_id);
                })
            ->pluck('event_id')->toArray();
        $ids = array_merge($ids1, $ids2);
        $ids = array_unique($ids);
        $events = Event::select($select)
            ->with(['category', 'theme'])
            ->withCount(['count_mypage', 'is_mypage'=>function($qr1)use($user_id){
                $qr1->where('user_id', $user_id);
            }])
            ->whereIn('id', $ids)
            ->where('seminar_id', $request->seminar_id);
            if($request->date) {
                $date_ar = json_decode($request->date);
                $events = $events->where(function($qr2)use($date_ar){
                    foreach ($date_ar as $key => $value) {
                        if($key==0){
                            $qr2 = $qr2->where(function($query)use($value){
                                $query->where('start_time','>=',$value.' 00:00:00')
                                    ->where('start_time','<=',$value.' 23:59:59');
                            });
                        }else{
                            $qr2 = $qr2->orWhere(function($query)use($value){
                                $query->where('start_time','>=',$value.' 00:00:00')
                                    ->where('start_time','<=',$value.' 23:59:59');
                            });
                        }
                    }
                });
            }
            $events = $events->orderBy('start_time', 'ASC')->paginate(10000);
            foreach ($events as $key => $value) {
                $value->date_time = date('Y年m月d日', strtotime($value->start_time)).'('.$value->thday.') '.date('H:i', strtotime($value->start_time)).'-'.date('H:i', strtotime($value->end_time));
            }

            $data["statusCode"] = 0;
            $data["message"] = "";
            $data['data'] = $events;  
        
        return response()->json($data);
    }
    public function api_list_by_global(Request $request)
    {
        // $eventsxx=[];
        $user_id = get_user_id($request);
        $seminar_id = $request->seminar_id;
        $keyword = $request->keyword;
        $resutlf = [];
        switch ($request->type) {
            case 1:
                $resutlf = $this->list_theme($seminar_id, $keyword, $request);
                break;
            case 2:
                $resutlf = $this->list_hl($seminar_id, $keyword, $request);
                break;
            case 3:
                $resutlf = $this->search_contact_global($seminar_id, $keyword, $request)['contacts'];
                break;
            case 4:
                $resutlf = $this->list_hm($seminar_id, $keyword, $request);
                break;
            default:
                # code...
                break;
        }
        // foreach ($events as $key => $value) {
        //     $value->date_time = date('Y年m月d日', strtotime($value->start_time)).'('.$value->thday.') '.date('H:i', strtotime($value->start_time)).'-'.date('H:i', strtotime($value->end_time));
        // }


        $data["statusCode"] = 0;
        $data["message"] = "";
        $data['data'] = $resutlf;  
        
        return response()->json($data);
    }

    public function api_search(Request $request)
    {
        // $eventsxx=[];
        $search = [];
        switch ($request->type) {
            case 0:
                $search = $this->search_global_func($request->seminar_id, $request->keyword, $request);
                break;
            case 1:
                $search = $this->search_category($request->seminar_id, $request->keyword, $request);
                break;
            case 2:
                $search = $this->search_theme($request->seminar_id, $request->keyword, $request);
                break;
            case 3:
                if($request->keyword==""){
                    $search = $this->search_contact($request->seminar_id, $request->keyword, $request)['contacts'];
                }else{
                    $search = $this->search_contact($request->seminar_id, $request->keyword, $request)['contacts'];
                }
                
                break;
            default:
                # code...
                break;
        }

        
        $data["statusCode"] = 0;
        $data["message"] = "";
        $data['data'] = $search;  
        
        
        
        return response()->json($data);
    }
    function list_theme($seminar_id, $keyword, $request){
    	$theme = ThemeEvent::whereHas('events', function($qr)use($seminar_id, $request){
            $qr->where('seminar_id', $seminar_id);
            if($request->date) {
                $date_ar = json_decode($request->date);
                $qr= $qr->where(function($qr2)use($date_ar){
                    foreach ($date_ar as $key => $value) {
                        if($key==0){
                            $qr2 = $qr2->where(function($query)use($value){
                                $query->where('start_time','>=',$value.' 00:00:00')
                                    ->where('start_time','<=',$value.' 23:59:59');
                            });
                        }else{
                            $qr2 = $qr2->orWhere(function($query)use($value){
                                $query->where('start_time','>=',$value.' 00:00:00')
                                    ->where('start_time','<=',$value.' 23:59:59');
                            });
                        }
                    }
                });
            }
        })
        ->where('name', 'LIKE','%'.$keyword.'%')
        ->where('name', '!=','')
        ->groupBy('name')
        ->paginate(10000);
        return $theme;
    }
    function list_hl($seminar_id, $keyword, $request){
    	$hl = EventDetail::select(['id', 'event_id', 'name',])
            ->where('name','LIKE', "%".$keyword."%")
            ->whereHas('events', function($qr)use($seminar_id, $request){
                $qr->where('seminar_id', $seminar_id);
                if($request->date) {
                    $date_ar = json_decode($request->date);
                    $qr= $qr->where(function($qr2)use($date_ar){
                        foreach ($date_ar as $key => $value) {
                            if($key==0){
                                $qr2 = $qr2->where(function($query)use($value){
                                    $query->where('start_time','>=',$value.' 00:00:00')
                                        ->where('start_time','<=',$value.' 23:59:59');
                                });
                            }else{
                                $qr2 = $qr2->orWhere(function($query)use($value){
                                    $query->where('start_time','>=',$value.' 00:00:00')
                                        ->where('start_time','<=',$value.' 23:59:59');
                                });
                            }
                        }
                    });
                }
            })
            // ->groupBy('name')
            ->paginate(10000);
        return $hl;
    }

    function list_hm($seminar_id, $keyword, $request){
    	$hm = EventDetail::select(['id', 'event_id', 'content',])
            ->where('content','LIKE', "%".$keyword."%")
            ->where('content','!=', "")
            ->whereHas('events', function($qr)use($seminar_id, $request){
                $qr->where('seminar_id', $seminar_id);
                if($request->date) {
                    $date_ar = json_decode($request->date);
                    $qr= $qr->where(function($qr2)use($date_ar){
                        foreach ($date_ar as $key => $value) {
                            if($key==0){
                                $qr2 = $qr2->where(function($query)use($value){
                                    $query->where('start_time','>=',$value.' 00:00:00')
                                        ->where('start_time','<=',$value.' 23:59:59');
                                });
                            }else{
                                $qr2 = $qr2->orWhere(function($query)use($value){
                                    $query->where('start_time','>=',$value.' 00:00:00')
                                        ->where('start_time','<=',$value.' 23:59:59');
                                });
                            }
                        }
                    });
                }
            })

            // ->groupBy('name')
            ->paginate(10000);
        return $hm;
    }
    function search_global_func($seminar_id, $keyword, $request){
        $select = ['id', 'seminar_id', 'category_event_id','theme_event_id', 'topic_number', 'name_basis','sponsored', 'hall', 'floor', 'room', 'year','month','day','thday','preside','start_time', 'end_time'];
        $theme = $this->list_theme($seminar_id, $keyword, $request);

        $hl = $this->list_hl($seminar_id, $keyword, $request);

        $pge = $this->search_contact($seminar_id, $keyword, $request);

        $hm = $this->list_hm($seminar_id, $keyword, $request);

        $data = [
            ['text' => 'テーマ','total' => $theme->total(),'type' => 1],
            ['text' => '演題名','total' => $hl->total(),'type' => 2],
            ['text' => '人名','total' => $pge['total'],'type' => 3],
            ['text' => '抄録本文','total' => $hm->total(),'type' => 4],
        ];
        return $data;
    }
    function search_category($seminar_id, $keyword, $request){
        // return $request->date;
        $cates = CategoryEvent::whereHas('events', function($qr)use($seminar_id, $request){
            $qr->where('seminar_id', $seminar_id);

            if($request->date) {
                $date_ar = json_decode($request->date);
                $qr= $qr->where(function($qr2)use($date_ar){
                    foreach ($date_ar as $key => $value) {
                        if($key==0){
                            $qr2 = $qr2->where(function($query)use($value){
                                $query->where('start_time','>=',$value.' 00:00:00')
                                    ->where('start_time','<=',$value.' 23:59:59');
                            });
                        }else{
                            $qr2 = $qr2->orWhere(function($query)use($value){
                                $query->where('start_time','>=',$value.' 00:00:00')
                                    ->where('start_time','<=',$value.' 23:59:59');
                            });
                        }
                    }
                });
            }
        })
        ->where('name_search','LIKE', '%'.$keyword.'%')
        ->groupBy('name_search')
        ->orderBy('name', 'DESC')
        ->paginate(10000);

        return $cates;
    }
    function search_contact_bk($seminar_id, $keyword, $request){
        $preside = $member = [];
        $keyword = str_replace('　', ' ', $keyword);
        for ($i=1; $i <=8 ; $i++) { 
            $pre = Event::select('id','preside'.$i.'_search_like as preside_search_like','preside'.$i.'_first_name as preside_first_name','preside'.$i.'_last_name as preside_last_name','preside'.$i.'_first_name_search as preside_first_name_search','preside'.$i.'_last_name_search as preside_last_name_search')
                ->where('preside'.$i.'_search_like','like', '%'.$keyword.'%')
                ->where('seminar_id', $seminar_id);
                if($request->date) {
                    $date_ar = json_decode($request->date);
                    $pre = $pre->where(function($qr2)use($date_ar){
                        foreach ($date_ar as $key => $value) {
                            if($key==0){
                                $qr2 = $qr2->where(function($query)use($value){
                                    $query->where('start_time','>=',$value.' 00:00:00')
                                        ->where('start_time','<=',$value.' 23:59:59');
                                });
                            }else{
                                $qr2 = $qr2->orWhere(function($query)use($value){
                                    $query->where('start_time','>=',$value.' 00:00:00')
                                        ->where('start_time','<=',$value.' 23:59:59');
                                });
                            }
                        }
                    });
                }
                $pre = $pre->where('preside'.$i.'_search_like', '!=', "")
                ->orderBy('preside'.$i.'_first_name_search', 'ASC')
                ->groupBy('preside_search_like')
                ->get()->toArray();
            $preside = array_merge($preside, $pre);
        }


        for ($i=1; $i <=25 ; $i++) { 
            $mem = EventDetail::select('id','member'.$i.'_search_like as preside_search_like','member'.$i.'_first_name as preside_first_name','member'.$i.'_last_name as preside_last_name','member'.$i.'_first_name_search as preside_first_name_search','member'.$i.'_last_name_search as preside_last_name_search')
                ->where('member'.$i.'_search_like','like', '%'.$keyword.'%')
                ->whereHas('events', function($qr)use($seminar_id,$request){
                    $qr->where('seminar_id', $seminar_id);
                    if($request->date) {
                        $date_ar = json_decode($request->date);
                        $qr= $qr->where(function($qr2)use($date_ar){
                            foreach ($date_ar as $key => $value) {
                                if($key==0){
                                    $qr2 = $qr2->where(function($query)use($value){
                                        $query->where('start_time','>=',$value.' 00:00:00')
                                            ->where('start_time','<=',$value.' 23:59:59');
                                    });
                                }else{
                                    $qr2 = $qr2->orWhere(function($query)use($value){
                                        $query->where('start_time','>=',$value.' 00:00:00')
                                            ->where('start_time','<=',$value.' 23:59:59');
                                    });
                                }
                            }
                        });
                    }
                })
                ->where('member'.$i.'_search_like', '!=', "")
                ->orderBy('preside_first_name_search', 'ASC')
                ->groupBy('preside_search_like')
                ->get()->toArray();
                // return $mem;
            $member = array_merge($member, $mem);
        }

        $userlist = array_merge($preside, $member);
        // return  $userlist;
        $order = array_column($userlist, 'preside_first_name_search');
        array_multisort($order, SORT_ASC, $userlist);

        $ar = $data = $ar2 = $arc = $dl = [];
        $userlist = array_unique($userlist, 0);
        $char = strtolower(mb_substr(@$userlist[0]['preside_first_name_search'], 0,1));
        // return $userlist;
        // $n = 0;
        foreach ($userlist as $key_s => $value_s) {
            $char_in = strtolower(mb_substr($value_s['preside_first_name_search'], 0,1));
            if(!in_array($value_s['preside_search_like'], $arc)) {
                // return [$char];
                if($char != $char_in || $key_s == count($userlist)-1) {
                    if($key_s == count($userlist)-1)  $data[] = $value_s;
                    $dl = [];
                    $dl['title'] = $char;
                    $dl['contact'] = $data;
                    if($this->is_ascii($char)){
                        $ar[] = $dl;
                    }else{
                        $ar2[] = $dl;
                    }
                    $char = $char_in;
                    $data = [];
                    $data[] = $value_s;
                }else{
                    $data[] = $value_s;
                }
                $arc[] = $value_s['preside_search_like'];
            }
        }

        $contact = [
            'total' => count($arc),
            'contacts' => array_merge($ar2, $ar),
        ];
        return $contact;
    }

    function search_contact($seminar_id, $keyword, $request){
        $preside = $member = [];
        $keyword = str_replace('　', ' ', $keyword);
        $presides = Event::where(function($qr)use($keyword){
                $qr->where('preside1_search_like', 'like', '%'.$keyword.'%');
                for ($ii=2; $ii <=8 ; $ii++) { 
                    $qr = $qr->orWhere('preside'.$ii.'_search_like', 'like', '%'.$keyword.'%');
                }
            })
            ->where('seminar_id', $request->seminar_id)->get()->toArray();

        $members = EventDetail::where(function($qr)use($keyword){
                $qr->where('member1_search_like', 'like', '%'.$keyword.'%');
                for ($ii=2; $ii <=25 ; $ii++) { 
                    $qr = $qr->orWhere('member'.$ii.'_search_like', 'like', '%'.$keyword.'%');
                }
            })
            ->whereHas('events', function($qr)use($request){
                    $qr->where('seminar_id', $request->seminar_id);
                })
            ->get()->toArray();
        if($keyword == "") $keyword = "xxx";
        if(count($presides)) {
            foreach ($presides as $key => $value) {
                for ($i=1; $i <=8 ; $i++) { 
                    $datax = [];
                    if($value['preside'.$i.'_search_like'] != "" && strpos('@xxxx'.$value['preside'.$i.'_search_like'], $keyword)) {
                        $datax['id'] = $value['id'];
                        $datax['preside_search_like'] = $value['preside'.$i.'_search_like'];
                        $datax['preside_first_name'] = $value['preside'.$i.'_first_name'];
                        $datax['preside_last_name'] = $value['preside'.$i.'_last_name'];
                        $datax['preside_first_name_search'] = $value['preside'.$i.'_first_name_search'];
                        $datax['preside_last_name_search'] = $value['preside'.$i.'_last_name_search'];
                        $preside[] = $datax;
                    }
                }
            }
        }
        if(count($members)) {
            foreach ($members as $key => $value) {
                for ($i=1; $i <=25 ; $i++) { 
                    $datax = [];
                    if($value['member'.$i.'_first_name'] != "" && strpos('@xxxx'.$value['member'.$i.'_search_like'], $keyword)){
                        $datax['id'] = $value['id'];
                        $datax['preside_search_like'] = $value['member'.$i.'_search_like'];
                        $datax['preside_first_name'] = $value['member'.$i.'_first_name'];
                        $datax['preside_last_name'] = $value['member'.$i.'_last_name'];
                        $datax['preside_first_name_search'] = $value['member'.$i.'_first_name_search'];
                        $datax['preside_last_name_search'] = $value['member'.$i.'_last_name_search'];
                        $member[] = $datax;
                    }
                }
            }
        }
        

        $userlist = array_merge($preside, $member);
        // return  $userlist;
        $order = array_column($userlist, 'preside_first_name_search');
        array_multisort($order, SORT_ASC, $userlist);

        $ar = $data = $ar2 = $arc = $dl = [];
        $userlist = array_unique($userlist, 0);
        $char = strtolower(mb_substr(@$userlist[0]['preside_first_name_search'], 0,1));
        // return $userlist;
        // $n = 0;
        foreach ($userlist as $key_s => $value_s) {
            $char_in = strtolower(mb_substr($value_s['preside_first_name_search'], 0,1));
            if(!in_array($value_s['preside_search_like'], $arc)) {
                // return [$char];
                if($char != $char_in || $key_s == count($userlist)-1) {
                    if($key_s == count($userlist)-1)  $data[] = $value_s;
                    $dl = [];
                    $dl['title'] = $char;
                    $dl['contact'] = $data;
                    if($this->is_ascii($char)){
                        $ar[] = $dl;
                    }else{
                        $ar2[] = $dl;
                    }
                    $char = $char_in;
                    $data = [];
                    $data[] = $value_s;
                }else{
                    $data[] = $value_s;
                }
                $arc[] = $value_s['preside_search_like'];
            }
        }

        $contact = [
            'total' => count($arc),
            'contacts' => array_merge($ar2, $ar),
        ];
        return $contact;
    }


    function search_theme($seminar_id, $keyword, $request){
        $select = ['id', 'seminar_id', 'category_event_id','theme_event_id', 'topic_number', 'name_basis','sponsored', 'hall', 'floor', 'room', 'year','month','day','thday','preside','start_time_view', 'end_time_view','start_time', 'end_time'];
        $theme = ThemeEvent::with(['events'=>function($qr)use($seminar_id, $select){
            $qr->select($select)->where('seminar_id', $seminar_id);
        }])
        ->whereHas('events', function($qr)use($seminar_id, $request){
            $qr->where('seminar_id', $seminar_id);
            if($request->date) {
                $date_ar = json_decode($request->date);
                $qr= $qr->where(function($qr2)use($date_ar){
                    foreach ($date_ar as $key => $value) {
                        if($key==0){
                            $qr2 = $qr2->where(function($query)use($value){
                                $query->where('start_time','>=',$value.' 00:00:00')
                                    ->where('start_time','<=',$value.' 23:59:59');
                            });
                        }else{
                            $qr2 = $qr2->orWhere(function($query)use($value){
                                $query->where('start_time','>=',$value.' 00:00:00')
                                    ->where('start_time','<=',$value.' 23:59:59');
                            });
                        }
                    }
                });
                
            }
        })
        ->where('name','LIKE', '%'.$keyword.'%')
        ->orderBy('name', 'DESC')
        ->paginate(10000);

        return $theme;
    }

    function search_contact_global($seminar_id, $keyword, $request){
        $preside = $member = [];
        $keyword = str_replace('　', ' ', $keyword);
        for ($i=1; $i <=8 ; $i++) { 
            $pre = Event::select('id','preside'.$i.'_search_like as preside_search_like','preside'.$i.'_first_name as preside_first_name','preside'.$i.'_last_name as preside_last_name','preside'.$i.'_first_name_search as preside_first_name_search','preside'.$i.'_last_name_search as preside_last_name_search')
                ->where('preside'.$i.'_search_like','like', '%'.$keyword.'%')
                ->where('seminar_id', $seminar_id);
                if($request->date) {
                    $date_ar = json_decode($request->date);
                    $pre = $pre->where(function($qr2)use($date_ar){
                        foreach ($date_ar as $key => $value) {
                            if($key==0){
                                $qr2 = $qr2->where(function($query)use($value){
                                    $query->where('start_time','>=',$value.' 00:00:00')
                                        ->where('start_time','<=',$value.' 23:59:59');
                                });
                            }else{
                                $qr2 = $qr2->orWhere(function($query)use($value){
                                    $query->where('start_time','>=',$value.' 00:00:00')
                                        ->where('start_time','<=',$value.' 23:59:59');
                                });
                            }
                        }
                    });
                }
                $pre = $pre->where('preside'.$i.'_search_like', '!=', "")
                ->orderBy('preside'.$i.'_first_name_search', 'ASC')
                ->groupBy('preside_search_like')
                ->get()->toArray();
            $preside = array_merge($preside, $pre);
        }


        for ($i=1; $i <=25 ; $i++) { 
            $mem = EventDetail::select('id','member'.$i.'_search_like as preside_search_like','member'.$i.'_first_name as preside_first_name','member'.$i.'_last_name as preside_last_name','member'.$i.'_first_name_search as preside_first_name_search','member'.$i.'_last_name_search as preside_last_name_search')
                ->where('member'.$i.'_search_like','like', '%'.$keyword.'%')
                ->whereHas('events', function($qr)use($seminar_id,$request){
                    $qr->where('seminar_id', $seminar_id);
                    if($request->date) {
                        $date_ar = json_decode($request->date);
                        $qr= $qr->where(function($qr2)use($date_ar){
                            foreach ($date_ar as $key => $value) {
                                if($key==0){
                                    $qr2 = $qr2->where(function($query)use($value){
                                        $query->where('start_time','>=',$value.' 00:00:00')
                                            ->where('start_time','<=',$value.' 23:59:59');
                                    });
                                }else{
                                    $qr2 = $qr2->orWhere(function($query)use($value){
                                        $query->where('start_time','>=',$value.' 00:00:00')
                                            ->where('start_time','<=',$value.' 23:59:59');
                                    });
                                }
                            }
                        });
                    }
                })
                ->where('member'.$i.'_search_like', '!=', "")
                ->orderBy('preside_first_name_search', 'ASC')
                ->groupBy('preside_search_like')
                ->get()->toArray();
                // return $mem;
            $member = array_merge($member, $mem);
        }

        $userlist = array_merge($preside, $member);
        // return  $userlist;
        $order = array_column($userlist, 'preside_first_name_search');
        array_multisort($order, SORT_ASC, $userlist);

        $ar = $data = $ar2 = $arc = $dl = [];
        $userlist = array_unique($userlist, 0);
        $char = strtolower(mb_substr(@$userlist[0]['preside_first_name_search'], 0,1));
        // return $userlist;
        // $n = 0;
        foreach ($userlist as $key_s => $value_s) {
            if(!in_array($value_s['preside_search_like'], $arc)) {
                $char = strtolower(mb_substr($value_s['preside_first_name_search'], 0,1));
                if($this->is_ascii($char)){
                    $ar[] = $value_s;
                }else{
                    $ar2[] = $value_s;
                }
                $arc[] = $value_s['preside_search_like'];
            }
        }

        $contact = [
            'total' => count($arc),
            'contacts' => array_merge($ar2, $ar),
        ];
        return $contact;
    }

    public function api_mypage_list_by_day(Request $request)
    {
        // return response()->json($request);
        $user_id = get_user_id($request);
        $events = [];
        $select = ['id', 'seminar_id', 'category_event_id','theme_event_id', 'topic_number', 'name_basis','sponsored', 'hall', 'floor', 'room', 'year','month','day','thday','preside','start_time_view', 'end_time_view','start_time', 'end_time'];
        // return response()->json($user_id);
        $events = Event::select($select)
            ->with(['category','theme','event_detail'=>function($qr){
                $qr->select(['id', 'event_id','topic_number','member', 'name', 'content']);
            },'ratings'=>function($qr)use($user_id){
                $qr->where('user_id', $user_id);
            }])
            ->withCount(['count_mypage', 'is_mypage'=>function($qr1)use($user_id){
                $qr1->where('user_id', $user_id);
            }])
            ->whereHas('count_mypage', function($qr)use($user_id){
                $qr->where('user_id', $user_id);
            })
            ->where('seminar_id', $request->seminar_id)
            ->where('start_time','>=',$request->date.' 00:00:00')
            ->where('start_time','<=',$request->date.' 23:59:59')
            // ->groupBy('hall','floor','room','month','day','start_time_view','end_time_view', 'category_event_id')
            ->orderBy('start_time', 'ASC')
            ->paginate(10);
        foreach ($events as $key => $value) {
            $value->date_time = date('Y年m月d日', strtotime($value->start_time)).'('.$value->thday.') '.date('H:i', strtotime($value->start_time)).'-'.date('H:i', strtotime($value->end_time));
            if(@$value->ratings[0]->rating == 1) @$value->ratings[0]->color = '#ff0000';
            elseif(@$value->ratings[0]->rating == 2) @$value->ratings[0]->color = '#1e00ff';
            else @$value->ratings[0]->color = '#f7962d';
            $cx = @$value->ratings[0];
            unset($value->ratings);
            $value->ratings = $cx;
        }
        if($events) {
            $data["statusCode"] = 0;
            $data["message"] = "";
            $data['data'] = $events;  
        }else{
            $data["statusCode"] = 0;
            $data["message"] = "";
            $data['data'] = $events;
        }
        
        
        return response()->json($data);
    }

    public function api_list_day(Request $request)
    {
        // return response()->json($request);
        $user_id = get_user_id($request);
        $events = [];
        $seminar = Seminar::find($request->seminar_id);
        // return response()->json($seminar);
        if($seminar) {
            $events = Event::select(DB::raw('DATE_FORMAT(start_time, "%Y-%m-%d") as day'))
                ->where('seminar_id', $request->seminar_id)
                ->where('start_time', '>=',date('Y-m-d',strtotime($seminar->start_date)))
                ->where('start_time', '<=',date('Y-m-d',strtotime($seminar->end_date)).' 23:59:59')
                ->groupBy('day')
                ->orderBy('day', 'ASC')
                ->get();
        }

        if($events) {
            $data["statusCode"] = 0;
            $data["message"] = "";
            $data['data'] = $events;  
        }else{
            $data["statusCode"] = 0;
            $data["message"] = "";
            $data['data'] = $events;
        }
        
        
        return response()->json($data);
    }

    
    public function api_mypage_list_day(Request $request)
    {
        // return response()->json($request);
        $user_id = get_user_id($request);
        $events = Event::select(DB::raw('DATE_FORMAT(start_time, "%Y-%m-%d") as day'))
            // ->withCount(['count_mypage', 'is_mypage'=>function($qr1)use($user_id){
            //     $qr1->where('user_id', $user_id);
            // }])
            ->whereHas('count_mypage', function($qr)use($user_id){
                $qr->where('user_id', $user_id);
            })
            ->where('seminar_id', $request->seminar_id)
            ->groupBy('day')
            ->get();
        if($events) {
            $data["statusCode"] = 0;
            $data["message"] = "";
            $data['data'] = $events;  
        }else{
            $data["statusCode"] = 0;
            $data["message"] = "";
            $data['data'] = $events;
        }
        
        
        return response()->json($data);
    }

    public function api_detail(Request $request)
    {
        $user_id = get_user_id($request);
        $events = Event::select('id', 'seminar_id', 'category_event_id','theme_event_id', 'topic_number', 'name_basis','sponsored', 'hall', 'floor', 'room', 'year','month','day','thday','start_time_view', 'end_time_view','start_time', 'end_time', 'preside', 'member')
            ->with(['category','event_detail'=>function($qr){
                $qr->select(['id', 'event_id','topic_number','member', 'name', 'content']);
            }, 'theme'])
            ->withCount(['count_mypage', 'is_mypage'=>function($qr1)use($user_id){
                $qr1->where('user_id', $user_id);
            }])
            ->where('id', $request->id)
            ->first();
        $events->date_time = date('Y年m月d日', strtotime($events->start_time)).'('.$events->thday.') '.date('H:i', strtotime($events->start_time)).'-'.date('H:i', strtotime($events->end_time));
        $data["statusCode"] = 0;
        $data["message"] = "";
        $data['data'] = $events;  
        return response()->json($data);
    }

    public function api_mypage_set(Request $request)
    {
        $user_id = get_user_id($request);
        $check = DB::table('user_events')->where('event_id', $request->id)->where('user_id', $user_id)->first();
        if($check) {
            $rs = DB::table('user_events')->where('event_id', $request->id)->where('user_id', $user_id)->delete();
            $rating = [];
        }else{
            $rs = DB::table('user_events')->insert([
                'event_id' => $request->id,
                'user_id' => $user_id,
                'rating' => 1,
            ]);
            $rating = [
            	'rating' => 1,
            	'color' => rating_color(1)
            ];
        }
        if($rs) {
            $data["statusCode"] = 0;
            $data["message"] = ""; 
            $data["data"] = ['rating'=>[]];
        }else{
            $data["statusCode"] = 1;
            $data["message"] = "error";
        }
        return response()->json($data);
    }

    public function event_set_rating(Request $request)
    {
        $user_id = get_user_id($request);

        $check = DB::table('user_events')->where('event_id', $request->id)->where('user_id', $user_id)->first();
        // return response()->json($request->id);
        if($check) {
            $rating = (($check->rating + 3) % 3) + 1;
            $rs = DB::table('user_events')->where('event_id', $request->id)->where('user_id', $user_id)->update(['rating'=>$rating]);
            $dl = [
                'rating' => $rating,
                'color' => rating_color($rating)
            ];
            $data["statusCode"] = 0;
            $data["message"] = ""; 
            $data["data"] = $dl; 
            return response()->json($data);
        }
        $data["statusCode"] = 1;
        $data["message"] = "error";
        return response()->json($data);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // $events = Event::all()->toArray();
        // foreach($events as $key => $value) {
        //     $data = [];
        //     for ($i=1; $i <=8; $i++) { 
        //         $data['preside'.$i.'_search_like'] = $value['preside'.$i.'_first_name_search'].' '.$value['preside'.$i.'_last_name_search'].' ___ '.$value['preside'.$i.'_first_name'].' '.$value['preside'.$i.'_last_name'];
        //     }
        //     Event::find($value['id'])->update($data);
        // }
        // $event_details = EventDetail::all();
        // foreach ($event_details as $key => $value) {
        //     $data = [];
        //     for ($i=1; $i <= 25; $i++) { 
        //         $data['member'.$i.'_search_like'] = $value['member'.$i.'_first_name_search'].' '.$value['member'.$i.'_last_name_search'].' ___ '.$value['member'.$i.'_first_name'].' '.$value['member'.$i.'_last_name'];
        //     }
        //     EventDetail::find($value['id'])->update($data);
        // }

        $events = Event::with(['category', 'theme'])
        ->where('seminar_id', $request->seminar_id);
       
        $events = $events->orderBy('start_time', 'ASC')->paginate(10);
        return view('events.index', compact(['events']));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        $theme = ThemeEvent::pluck('id', 'name');
        $categories = CategoryEvent::pluck('name','id');
        return view('events.create', compact(['theme','categories']));
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
        $seminar = Seminar::find($request->seminar_id);
        $validator = Validator::make(
            $request->all(), 
            [
                'name' => 'required',            ],
            [
                'name.required' => 'nameが間違っています。',
            ]
        );
        if(strtotime($request->start_time) > strtotime($request->end_time)) {
            $validator->after(function ($validator) {
                 $validator->errors()->add('end_time.format', '終了日が開始日より大きい');
            });
        }
        // dd($request->start_time .' ' .$request->end_time .' '. $seminar->start_date .' '. $seminar->end_date);

        if(strtotime($request->start_time) < strtotime($seminar->start_date) || strtotime($request->start_time) > strtotime($seminar->end_date." 23:59:59")) {
            $validator->after(function ($validator) {
                 $validator->errors()->add('start_time.format', '終了日が開始日より大きい');
            });
        }
        if(strtotime($request->end_time) < strtotime($seminar->start_date) || strtotime($request->end_time." 23:59:59") > strtotime($seminar->end_date)) {
            $validator->after(function ($validator) {
                 $validator->errors()->add('start_time.format', '終了日が開始日より大きい');
            });
        }

        if ($validator->fails()) {
            return redirect(route('events.create'))
                        ->withErrors($validator)
                        ->withInput();
        }

        $data = $request->all();
        unset($data['_token']);
        $rs = Event::insertGetId($data);
        if($rs) {
            return redirect(route('events.index').'?seminar_id='.$request->seminar_id);
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
        $theme = ThemeEvent::pluck('name','id');
        $categories = CategoryEvent::pluck('name','id');
        $event = Event::with(['category', 'theme', 'event_detail'])
            ->where('id', $id)->first();
        // $namects = Event::select('id','name', 'content', 'member')
        //     ->where('hall',$event->hall)
        //     ->where('floor',$event->floor)
        //     ->where('room',$event->room)
        //     ->where('month',$event->month)
        //     ->where('day',$event->day)
        //     ->where('start_time_view',$event->start_time_view)
        //     ->where('end_time_view',$event->end_time_view)
        //     ->get();
        return view('events.edit', compact(['event', 'theme', 'categories']));
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
        $seminar = Seminar::find($request->seminar_id);
        $validator = Validator::make(
            $request->all(), 
            [
                'name' => 'required',            ],
            [
                'name.required' => 'nameが間違っています。',
            ]
        );
        if(strtotime($request->start_time) > strtotime($request->end_time)) {
            $validator->after(function ($validator) {
                 $validator->errors()->add('end_time.format', '終了日が開始日より大きい');
            });
        }
        if(strtotime($request->start_time) < strtotime($seminar->start_date) || strtotime($request->start_time) > strtotime($seminar->end_date ." 23:59:59")) {
            $validator->after(function ($validator) {
                 $validator->errors()->add('start_time.format', '終了日が開始日より大きい');
            });
        }
        if(strtotime($request->end_time) < strtotime($seminar->start_date) || strtotime($request->end_time) > strtotime($seminar->end_date ." 23:59:59")) {
            $validator->after(function ($validator) {
                 $validator->errors()->add('start_time.format', '終了日が開始日より大きい');
            });
        }

        if ($validator->fails()) {
            return back()
                        ->withErrors($validator)
                        ->withInput();
        }


        $data = $request->all();
        unset($data['_token']);
        unset($data['_method']);
        unset($data['name']);
        unset($data['content']);
        unset($data['member']);
        unset($data['topic_number']);

        $data['start_time_view'] = date('H:i', strtotime($request->start_time));
        $data['end_time_view'] = date('H:i', strtotime($request->start_time));
        $data['year'] = date('Y', strtotime($request->start_time));
        $data['mounth'] = date('m', strtotime($request->start_time));
        $data['day'] = date('d', strtotime($request->start_time));
        $data['thday'] = get_day_text(date('Y-m-d', strtotime($request->start_time)));
        // dd($data);
        $rs = Event::find($id)->update($data);
        if($rs) {
            if($request->name){
                foreach ($request->name as $keyn => $valuen) {
                    $dl = [
                        'name'=>$request->name[$keyn],
                        'content'=>$request->content[$keyn],
                        'member'=>$request->member[$keyn],
                        'topic_number'=>$request->topic_number[$keyn],
                    ];
                    $rs1 = EventDetail::find($keyn)->update($dl);
                }
            }
            return redirect(route('events.index').'?seminar_id='.Event::find($id)->seminar_id);
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
    public function destroy($id, Request $request)
    {
        //
        UserEvent::where('event_id', $id)->delete();
        Event::destroy($id);
        return redirect(route('events.index').'?seminar_id='.$request->seminar_id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function deleteall($id)
    {
        //
        $detail_ids = Event::where('seminar_id', $id)->pluck('id')->toArray();
        EventDetail::whereIn('event_id', $detail_ids)->delete();
        UserEvent::whereIn('event_id', $detail_ids)->delete();
        ViewEvent::whereIn('event_id', $detail_ids)->delete();
        ThemeEvent::where('seminar_id', $id)->delete('id');
        Event::where('seminar_id', $id)->delete('id');
        return redirect(route('events.index').'?seminar_id='.$id);
    }


     /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function getevents()
    {
        //
        return view('events.getevent', compact(['event']));
    }

     /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function postevents(Request $request)
    {
        //
        // require_once './app/Helpers/PHPExcel.php';
        
        // \PHPExcel_Settings::setZipClass(PHPExcel_Settings::PCLZIP);   
        $file = $request->event_excel->path();
        $objFile = \PHPExcel_IOFactory::identify($file);
        $objData = \PHPExcel_IOFactory::createReader($objFile);
        $objData->setReadDataOnly(true);
        $objPHPExcel = $objData->load($file);
        $sheet = $objPHPExcel->setActiveSheetIndex(0);

        //Lấy ra số dòng cuối cùng
        $Totalrow = $sheet->getHighestRow();
        //Lấy ra tên cột cuối cùng
        $LastColumn = $sheet->getHighestColumn();

        //Chuyển đổi tên cột đó về vị trí thứ, VD: C là 3,D là 4
        $TotalCol = \PHPExcel_Cell::columnIndexFromString($LastColumn);

        //Tạo mảng chứa dữ liệu
        $data = [];

        //Tiến hành lặp qua từng ô dữ liệu
        //----Lặp dòng, Vì dòng đầu là tiêu đề cột nên chúng ta sẽ lặp giá trị từ dòng 2
        for ($i = 2; $i <= $Totalrow; $i++) {
            $topic_number = $sheet->getCellByColumnAndRow(0, $i)->getValue() != "" ? $sheet->getCellByColumnAndRow(0, $i)->getValue() : "";
            $category = $sheet->getCellByColumnAndRow(1, $i)->getValue() != "" ? $sheet->getCellByColumnAndRow(1, $i)->getValue() : "";
            $category_s = $sheet->getCellByColumnAndRow(2, $i)->getValue() != "" ? $sheet->getCellByColumnAndRow(2, $i)->getValue() : "";
            $theme = $sheet->getCellByColumnAndRow(3, $i)->getValue() != "" ? $sheet->getCellByColumnAndRow(3, $i)->getValue() : "";
            $year = date('Y');
            $month = $sheet->getCellByColumnAndRow(4, $i)->getValue() != "" ? $sheet->getCellByColumnAndRow(4, $i)->getValue() : "";
            $day = $sheet->getCellByColumnAndRow(5, $i)->getValue() != "" ? $sheet->getCellByColumnAndRow(5, $i)->getValue() : "";
            $thday = $sheet->getCellByColumnAndRow(6, $i)->getValue() != "" ? $sheet->getCellByColumnAndRow(6, $i)->getValue() : "";
            $start_time_view = \PHPExcel_Style_NumberFormat::toFormattedString($sheet->getCellByColumnAndRow(7, $i)->getCalculatedValue(), 'hh:mm');
            $end_time_view = \PHPExcel_Style_NumberFormat::toFormattedString($sheet->getCellByColumnAndRow(8, $i)->getCalculatedValue(), 'hh:mm');
            $name_basis = $sheet->getCellByColumnAndRow(9, $i)->getValue() != "" ? $sheet->getCellByColumnAndRow(9, $i)->getValue() : "";
            $hall = $sheet->getCellByColumnAndRow(10, $i)->getValue() != "" ? $sheet->getCellByColumnAndRow(10, $i)->getValue() : "";
            $floor = $sheet->getCellByColumnAndRow(11, $i)->getValue() != "" ? $sheet->getCellByColumnAndRow(11, $i)->getValue() : "";
            $room = $sheet->getCellByColumnAndRow(12, $i)->getValue() != "" ? $sheet->getCellByColumnAndRow(12, $i)->getValue() : "";
            $sponsored = $sheet->getCellByColumnAndRow(13, $i)->getValue() != "" ? $sheet->getCellByColumnAndRow(13, $i)->getValue() : "";
            $name = $sheet->getCellByColumnAndRow(219, $i)->getValue() != "" ? $sheet->getCellByColumnAndRow(219, $i)->getValue() : "";
            $content = $sheet->getCellByColumnAndRow(220, $i)->getValue() != "" ? $sheet->getCellByColumnAndRow(220, $i)->getValue() : "";
            // $content = '<div style="font-size: 14px">'. $content. '</div>';
            $start_time = $year.'-'.$month.'-'.$day.' '.$start_time_view;
            $end_time = $year.'-'.$month.'-'.$day.' '.$end_time_view;
            $start_time = date('Y-m-d H:i:s', strtotime($start_time));
            $end_time = date('Y-m-d H:i:s', strtotime($end_time));
            $preside_name = $member_name = [];
            for ($yy=15; $yy <= 57; $yy=$yy+6) {
                $data_pre['preside_first_name'] = $sheet->getCellByColumnAndRow($yy, $i)->getValue() != "" ? $sheet->getCellByColumnAndRow($yy, $i)->getValue() : "";
                $data_pre['preside_last_name'] = $sheet->getCellByColumnAndRow($yy+1, $i)->getValue() != "" ? $sheet->getCellByColumnAndRow($yy+1, $i)->getValue() : "";
                $data_pre['preside_first_name_search'] = $sheet->getCellByColumnAndRow($yy+2, $i)->getValue() != "" ? $sheet->getCellByColumnAndRow($yy+2, $i)->getValue() : "";
                $data_pre['preside_last_name_search'] = $sheet->getCellByColumnAndRow($yy+3, $i)->getValue() != "" ? $sheet->getCellByColumnAndRow($yy+3, $i)->getValue() : "";
                $data_pre['preside_search_like'] = $data_pre['preside_first_name_search'].' '.$data_pre['preside_last_name_search'] . '___' . $data_pre['preside_first_name'].' '.$data_pre['preside_last_name'];
                $preside_name[] = $data_pre;
            }

            $preside = $member = $search_c = '';
            for ($iy=15; $iy <= 57; $iy=$iy+6) { 
                
                if($sheet->getCellByColumnAndRow($iy, $i)->getValue() != "") {
                    if($iy>15 && $iy<57) $preside = $preside.',';
                    $preside = $preside.$sheet->getCellByColumnAndRow($iy, $i)->getValue();
                }
                if($sheet->getCellByColumnAndRow($iy+1, $i)->getValue() != "") {
                    $preside = $preside.' '.$sheet->getCellByColumnAndRow($iy+1, $i)->getValue();
                }
                if($sheet->getCellByColumnAndRow($iy+4, $i)->getValue() != "") {
                    $preside = $preside.'('.$sheet->getCellByColumnAndRow($iy+4, $i)->getValue().')';
                }
            }
            
            
            for ($ix=62; $ix <= 182; $ix=$ix+5) { 

                $data_mem['member_first_name'] = $sheet->getCellByColumnAndRow($ix, $i)->getValue() != "" ? $sheet->getCellByColumnAndRow($ix, $i)->getValue() : "";
                $data_mem['member_last_name'] = $sheet->getCellByColumnAndRow($ix+1, $i)->getValue() != "" ? $sheet->getCellByColumnAndRow($ix+1, $i)->getValue() : "";
                $data_mem['member_first_name_search'] = $sheet->getCellByColumnAndRow($ix+1, $i)->getValue() != "" ? $sheet->getCellByColumnAndRow($ix+2, $i)->getValue() : "";
                $data_mem['member_last_name_search'] = $sheet->getCellByColumnAndRow($ix+1, $i)->getValue() != "" ? $sheet->getCellByColumnAndRow($ix+3, $i)->getValue() : "";
                $data_mem['member_search_like'] = $data_mem['member_first_name_search'].' '.$data_mem['member_last_name_search'].' ___ '.$data_mem['member_first_name'].' '.$data_mem['member_last_name'];
                $member_name[] = $data_mem;

                if($sheet->getCellByColumnAndRow($ix, $i)->getValue() != "") {
                    if($ix>62) $member = $member.',';
                    $member = $member.$sheet->getCellByColumnAndRow($ix, $i)->getValue();
                }
                if($sheet->getCellByColumnAndRow($ix+1, $i)->getValue() != "") {
                    $member = $member.' '.$sheet->getCellByColumnAndRow($ix+1, $i)->getValue();
                }
                if($sheet->getCellByColumnAndRow($ix+4, $i)->getValue() != "") {
                    $member = $member.'<sup>'.$sheet->getCellByColumnAndRow($ix+4, $i)->getValue().'</sup>';
                }
            }
            // dd($member_name);
            $member = $member."<br />";
            for ($ii=187; $ii <= 217; $ii=$ii+2) { 
                if($sheet->getCellByColumnAndRow($ii+1, $i)->getValue() != "") {
                    $member = $member.$sheet->getCellByColumnAndRow($ii, $i)->getValue().")";
                    $member = $member.$sheet->getCellByColumnAndRow($ii+1, $i)->getValue()." ";
                }
            }
            $member = $member."<br />";
            // $member = '<div style="font-size: 14px">' . $member . '</div>';

            // if($sheet->getCellByColumnAndRow(188, $i)->getValue() != "") {
            //     $member = $member."<br />".$sheet->getCellByColumnAndRow(188, $i)->getValue();
            // }

            for ($jj=15; $jj < 187; $jj++) { 
                $search_c = $search_c.' '.$sheet->getCellByColumnAndRow($jj, $i)->getValue();
            }

            

            $category_event_id = 0;
            if($category != "") {
                $category_event = CategoryEvent::where('name', $category)->where('seminar_id', $request->seminar_id)->first();
                if($category_event) {
                    $category_event_id = $category_event->id;
                }else{
                    $category_event_id = CategoryEvent::insertGetId([
                            'seminar_id' => $request->seminar_id,
                            'name' => $category,
                            'name_search' => $category_s,
                            'color' => '#ffffff'
                        ]);
                }
            }
            
            $theme_event_id = 0;
            if($theme!="") {
                $theme_event = ThemeEvent::where('name', $theme)->where('seminar_id', $request->seminar_id)->first();
                if($theme_event) {
                    $theme_event_id = $theme_event->id;
                }else{
                    $theme_event_id = ThemeEvent::insertGetId([
                            'seminar_id' => $request->seminar_id,
                            'name' => $theme
                        ]);
                }
            }
            $hall_id = 0;
            if($hall!="") {
                $hallCheck = Hall::where('name', $hall)->where('seminar_id', $request->seminar_id)->first();
                if($hallCheck) {
                    $hall_id = $hallCheck->id;
                }else{
                    $hall_id = Hall::insertGetId([
                            'seminar_id' => $request->seminar_id,
                            'name' => $hall,
                            'nabi' => 999999999
                        ]);
                }
            }
            
            $ck = [
                'seminar_id' => (int)$request->seminar_id,
                'category_event_id' => $category_event_id,
                'theme_event_id' => $theme_event_id,
                'start_time' => $start_time,
                'end_time' => $end_time,
                'hall' => $hall,
                'floor' => $floor,
                'room' => $room,
            ];
            $data = [
                'hall_id' => $hall_id,
                'year' => $year,
                'month' => $month,
                'day' => $day,
                'thday' => $thday,
                'start_time_view' => $start_time_view,
                'end_time_view' => $end_time_view,
                'preside' => $preside,
                'name_basis' => $name_basis,
                'sponsored' => $sponsored,
                'name' => $name,
                'topic_number' => $topic_number,
                'member' => $member,
                'content' => $content,
                'search_c' => $search_c
            ];
            foreach ($preside_name as $key_pre => $value_pre) {
                $vt = $key_pre+1;
                $data['preside'.$vt.'_first_name'] = $value_pre['preside_first_name'];
                $data['preside'.$vt.'_last_name'] = $value_pre['preside_last_name'];
                $data['preside'.$vt.'_first_name_search'] = $value_pre['preside_first_name_search'];
                $data['preside'.$vt.'_last_name_search'] = $value_pre['preside_last_name_search'];
                $data['preside'.$vt.'_search_like'] = $value_pre['preside_search_like'];
            }
            // dd($data);
            if($day !="") {
                $obj = Event::updateOrCreate($ck, $data);

                // dd($obj);
                $ck1 = [
                    'event_id' => $obj->id,
                    'name' => $name,
                ];
                $data1 = [
                    'content' => $content,
                    'topic_number' => $topic_number,
                    'member' => $member,
                    'search_c' => $search_c
                ];
                foreach ($member_name as $key_mem => $value_mem) {
                    $vt = $key_mem+1;
                    ($value_mem['member_first_name']) ? $data1['member'.$vt.'_first_name'] = $value_mem['member_first_name'] : $data1['member'.$vt.'_first_name'] = '';
                    ($value_mem['member_last_name']) ? $data1['member'.$vt.'_last_name'] = $value_mem['member_last_name'] : $data1['member'.$vt.'_last_name'] = "";
                    ($value_mem['member_first_name_search'])? $data1['member'.$vt.'_first_name_search'] = $value_mem['member_first_name_search'] : $data1['member'.$vt.'_first_name_search'] = "";
                    ($value_mem['member_last_name_search'])? $data1['member'.$vt.'_last_name_search'] = $value_mem['member_last_name_search'] : $data1['member'.$vt.'_last_name_search'] = "";
                    ($value_mem['member_search_like'])? $data1['member'.$vt.'_search_like'] = $value_mem['member_search_like'] : $data1['member'.$vt.'_search_like'] = "";
                }
                // dd($data);
                EventDetail::updateOrCreate($ck1, $data1);
            }
            
        }
        return redirect(route('events.index').'/?seminar_id='.$request->seminar_id);
    }
    public function generatePDF(Request $request)
    {
        $user_id = get_user_id($request);
        $seminar_id = Seminar::where('category_id', $request->association_id)->first()->id;
        $event_days = Event::select(DB::raw('DATE_FORMAT(start_time, "%Y-%m-%d") as day'))
            ->whereHas('count_mypage', function($qr)use($user_id){
                $qr->where('user_id', $user_id);
            })
            ->where('seminar_id', $seminar_id)
            ->groupBy('day')
            ->get();
        $association = Category::find($request->association_id);
        $select = ['id', 'seminar_id', 'category_event_id','theme_event_id', 'topic_number', 'name_basis', 'hall', 'floor', 'room', 'year','month','day','thday','preside','start_time_view', 'end_time_view','start_time', 'end_time'];
        $events = [];
        if(count($event_days)) {
            foreach ($event_days as $key => $value) {
                $events[$value->day] = $this->get_list_event_fc($user_id, $seminar_id, $value->day, $select);
            }
        }
        $datapdf = [
            'events' => $events,
            'association' => $association,
        ];
        
       // return response()->json($datapdf);
        $html = view('events.myPDF', $datapdf)->render();
        // $html = mb_convert_encoding($html, "UTF-8", "JIS, eucjp-win, sjis-win, UTF-8*");
        $pdf = PDF::loadHtml($html)->setPaper('a4');
        // $pdf = PDF::loadView('events.myPDF', $datapdf);
        $filename = 'event_'.strtotime(date('Ymdhis')).'.pdf';
        $path = '/var/www/html/storage/app/seminar/events/'.$filename;
        $url = route('login').'/storage/app/seminar/events/'.$filename;
        $pdf->save($path);
        
        $data["statusCode"] = 0;
        $data["message"] = "";
        $data['data'] = ['pdf'=>$url];
        return response()->json($data);
    }
    public function generatePDF1(Request $request)
    {
        // $user_id = 300;
        // $seminar_id = Seminar::where('category_id', $request->association_id)->first()->id;
        // $event_days = Event::select(DB::raw('DATE_FORMAT(start_time, "%Y-%m-%d") as day'))
        //     ->whereHas('count_mypage', function($qr)use($user_id){
        //         $qr->where('user_id', $user_id);
        //     })
        //     ->where('seminar_id', $seminar_id)
        //     ->groupBy('day')
        //     ->get();
        // $association = Category::find($request->association_id);
        // $select = ['id', 'seminar_id', 'category_event_id','theme_event_id', 'topic_number', 'name_basis', 'hall', 'floor', 'room', 'year','month','day','thday','preside','start_time_view', 'end_time_view','start_time', 'end_time'];
        // $events = [];
        // if(count($event_days)) {
        //     foreach ($event_days as $key => $value) {
        //         $events[$value->day] = $this->get_list_event_fc($user_id, $seminar_id, $value->day, $select);
        //     }
        // }
        // $datapdf = [
        //     'events' => $events,
        //     'association' => $association,
        // ];
       // return response()->json($datapdf);
        // $pdf = PDF::loadView('events.myPDF', $datapdf);
        // $filename = 'event_'.strtotime(date('Ymdhis')).'.pdf';
        // $path = '/var/www/html/storage/app/seminar/events/'.$filename;
        // $url = route('login').'/storage/app/seminar/events/'.$filename;
        // $pdf->save($path);
        // return view('events.myPDF', compact(['events', 'association']));
        $html = view('events.a4')->render();
        $pdf = PDF::loadHtml($html)->setPaper('a4');
        // return $pdf->stream();
        $pdf->save("report.pdf");
        die();
    }
    function is_ascii( $string = '' ) {
        return ( bool ) ! preg_match( '/[\\x80-\\xff]+/' , $string );
    }
    function get_list_event_fc($user_id, $seminar_id, $date, $select){
        $events = Event::select($select)
            ->with(['category','theme'=>function($qr){
                $qr->with(['events'=>function($qr1){
                    $qr1->select(['id', 'seminar_id', 'category_event_id','theme_event_id', 'name']);
                }]);
            }])
            ->withCount(['count_mypage', 'is_mypage'=>function($qr1)use($user_id){
                $qr1->where('user_id', $user_id);
            }])
            ->whereHas('count_mypage', function($qr)use($user_id){
                $qr->where('user_id', $user_id);
            })
            ->where('seminar_id', $seminar_id)
            ->where('start_time','>=',$date.' 00:00:00')
            ->where('start_time','<=',$date.' 23:59:59')
            // ->groupBy('hall','floor','room','month','day','start_time_view','end_time_view', 'category_event_id')
            ->get();
        return $events;
    }
    public function push_event_15(){

        $date15 = date('Y-m-d H:i', strtotime('+ 15 minutes'));
        // dd($date15);
        $events = Event::with('user_events', 'user_events.device')
        ->where(DB::raw('DATE_FORMAT(start_time, "%Y-%m-%d %H:%i")') ,$date15)
        ->has('user_events')
        ->get();
        // dd($events);
        // $event = Event::with('user_events', 'user_events.device')
        // ->take(4)
        // ->get();
        foreach ($events as $key => $value) {
            $title = '【演題開始15分前】登録した演題がまもなくはじまります。';
            $body = '';
            $push_data = [
                'title' => $title,
                'body' => $body,
                'type' => 1,
                'tab' => 1,
                'association_id' => Seminar::find($value->seminar_id)->category_id,
                'seminar_id' => $value->seminar_id,
            ];
            // dd($events->user_events);
            foreach (@$value->user_events as $ku => $vu) {
                foreach (@$vu->device as $kd => $vd) {
                    // echo $vd->token.'<br>';
                    notification_push($vd->token, $title, $push_data, $body);
                }
            }
            
        }
        
        die();
    }
}
