<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Topic;
use App\Category;
use DB;
use Validator;
class TopicController extends Controller
{
    // Api 

    public function api_list_month(Request $request)
    {
        $user_id = get_user_id($request);
        $callback = function($query)use($user_id) {
            $query->where('user_id', $user_id);
        };
        $categories = Category::whereHas('user_category', $callback)->with(['user_category' => $callback])->where('publish', 0)->pluck('id');
        // $topic = Topic::All();
        $topic = Topic::select(DB::raw('DATE_FORMAT(start_date, "%Y-%m") as month'))
            ->whereIn('category_id', $categories)
            ->where('category_id', $request->association_id)
            ->where('start_date', '>=', date('Y-m-d'))
            ->groupBy('month')
            ->orderBy('month','ASC')
            ->get();
        if(count($topic)) {
            $data["statusCode"] = 0;
            $data["message"] = "";
            $data['data'] = $topic;  
        }else{
            $data["statusCode"] = 0;
            $data["message"] = "";
            $data['data'] = $topic;
        }
        
        return response()->json($data);
    }

    public function api_list_by_month(Request $request)
    {
        $user_id = get_user_id($request);
        $callback = function($query)use($user_id) {
            $query->where('user_id', $user_id);
        };
        $categories = Category::whereHas('user_category', $callback)->with(['user_category' => $callback])->where('publish', 0)->pluck('id');
        $s_date = $request->month.'-01';
        $e_date = $request->month.'-'.date('t',strtotime($s_date));

        // $topic = Topic::All();
        $topic = Topic::select('id', 'name', 'start_date', 'start_sunday', 'end_date', 'end_sunday', 'building', 'address', 'map', 'url', 'url_text', 'unit')
            ->whereIn('category_id', $categories)
            ->where('category_id', $request->association_id)
            ->where('start_date', '>=', date('Y-m-d'))
            ->where('start_date','>', $s_date)
            ->where('start_date','<', $e_date)
            ->orderBy('start_date','ASC')
            ->take(10)
            ->get();
        if(count($topic)) {
            $data["statusCode"] = 0;
            $data["message"] = "";
            $data['data'] = $topic;  
        }else{
            $data["statusCode"] = 0;
            $data["message"] = "";
            $data['data'] = $topic;
        }
        
        return response()->json($data);
    }
    
    public function api_list_limit(Request $request)
    {
        $user_id = get_user_id($request);
        $callback = function($query)use($user_id) {
            $query->where('user_id', $user_id);
        };
        $categories = Category::whereHas('user_category', $callback)->with(['user_category' => $callback])->where('publish', 0)->pluck('id');
        
        $topic = Topic::select('id', 'name', 'start_date', 'start_sunday', 'end_date', 'end_sunday', 'building', 'address', 'map', 'url', 'url_text', 'unit')
            ->whereIn('category_id', $categories)
            ->where('category_id', $request->association_id)
            ->where('start_date','>=',date('Y-m-d'))
            ->orderBy('start_date','ASC')
            // ->offset($offset)
            ->take(10)
            ->get();
        if(count($topic)) {
            $data["statusCode"] = 0;
            $data["message"] = "";
            $data['data'] = $topic;  
        }else{
            $data["statusCode"] = 0;
            $data["message"] = "";
            $data['data'] = $topic;
        }
        
        return response()->json($data);
    }

    public function api_detail(Request $request)
    {
        $user_id = get_user_id($request);
        $callback = function($query)use($user_id) {
            $query->where('user_id', $user_id);
        };
        $categories = Category::whereHas('user_category', $callback)->with(['user_category' => $callback])->where('publish', 0)->pluck('id');
        // return response()->json($categories);

        // $topic = Topic::All();
        $topic = Topic::select('id', 'name', 'start_date', 'start_sunday', 'end_date', 'end_sunday', 'building', 'address', 'map', 'url', 'url_text', 'unit')
            ->whereIn('category_id', $categories)
            ->where('id', $request->id)->first();
        if($topic) {
            $data["statusCode"] = 0;
            $data["message"] = "";
            $data['data'] = $topic;  
        }else{
            $data["statusCode"] = 1;
            $data["message"] = "Topic is not in association";
            $data['data'] = $topic;
        }
        
        return response()->json($data);
    }


        /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index( Request $request)
    {
        // 
        $categories = Category::pluck('category','id');
        $topics = Topic::where('id','>',0);
        if($request->category_id){
            $topics = $topics->where('category_id', $request->category_id);
        }
        $topics = $topics->orderBy('id', 'DESC')->paginate(20);
        // dd($topic);
        return view('topic.index',compact(['topics','categories']));
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
        return view('topic.create', compact(['categories']));
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
        $validator = Validator::make(
            $request->all(), 
            [
                'name' => 'required',            ],
            [
                'name.required' => 'メールアドレスが間違っています。',
            ]
        );
        if(strtotime($request->start_date) > strtotime($request->end_date)) {
            $validator->after(function ($validator) {
                 $validator->errors()->add('end_date.format', '終了日が開始日より大きい');
            });
        }
        if ($validator->fails()) {
            return redirect(route('topics.create'))
                        ->withErrors($validator)
                        ->withInput();
        }

        $data = $request->all();
        unset($data['_token']);
        $rs = Topic::insertGetId($data);
        if($rs) {
            Topic::find($rs)->update(['start_sunday'=>get_day_text($request->start_date),'end_sunday'=>get_day_text($request->end_date)]);
            return redirect(route('topics.index'));
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
        $categories = Category::pluck('category','id');
        $topic = Topic::find($id);
        return view('topic.edit', compact(['categories','topic']));
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
        $validator = Validator::make(
            $request->all(), 
            [
                'name' => 'required',            ],
            [
                'name.required' => 'メールアドレスが間違っています。',
            ]
        );
        if(strtotime($request->start_date) > strtotime($request->end_date)) {
            $validator->after(function ($validator) {
                 $validator->errors()->add('end_date.format', '終了日が開始日より大きい');
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
        $data['start_sunday'] = get_day_text($data['start_date']);
        $data['end_sunday'] = get_day_text($data['end_date']);
        $rs = Topic::find($id)->update($data);
        if($rs) {
            return redirect(route('topics.index'));
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
        Topic::destroy($id);
        return redirect(route('topics.index'));
    }

       /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function gettopics()
    {
        //
        $categories = Category::pluck('category','id');
        return view('topic.gettopic', compact(['categories']));
    }

        /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function posttopics(Request $request)
    {
        // $categories = Category::select('id', 'code')->pluck('code', 'id')->toArray();
         // dd(array_search('1041',$categories));
        $file = $request->topic_excel->path();
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
            if(!empty($sheet->getCellByColumnAndRow(0, $i)->getValue()) || !empty($sheet->getCellByColumnAndRow(2, $i)->getValue())) {
                // dd($sheet->getCellByColumnAndRow(3, $i)->getFormattedValue());
                $start = $sheet->getCellByColumnAndRow(0, $i)->getValue();
                $start_date = date('Y-m-d', \PHPExcel_Shared_Date::ExcelToPHP($start));
                $end = $sheet->getCellByColumnAndRow(2, $i)->getValue();
                $end_date = date('Y-m-d', \PHPExcel_Shared_Date::ExcelToPHP($end));

                $ar = [
                    'category_id' => $request->category_id,
                    'url' => $sheet->getCellByColumnAndRow(7, $i)->getValue(),
                ];
                $dl = [
                    
                    'start_date' => date('Y-m-d', strtotime($start_date)),
                    'end_date' => date('Y-m-d', strtotime($end_date)),
                    'start_sunday' => get_day_text(date('Y-m-d', strtotime($start_date))),
                    'end_sunday' => get_day_text(date('Y-m-d', strtotime($end_date))),
                    'name' => $sheet->getCellByColumnAndRow(5, $i)->getValue(),
                    'unit' => $sheet->getCellByColumnAndRow(6, $i)->getValue(),
                    'building' => $sheet->getCellByColumnAndRow(8, $i)->getValue(),
                    'address' => $sheet->getCellByColumnAndRow(9, $i)->getValue()
                ];
                // dd($dl);
                Topic::updateOrCreate($ar,$dl);
            }
            
        }  
        // return redirect(route('members.index'));
        return redirect(route('topics.index').'/?category_id='.$request->category_id);
    }
}
