<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Question;
use App\Answer;

class QuestionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //
        $questions = Question::where('video_id', $request->video_id)->paginate(20);
        return view('questions.index', compact(['questions']));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        //
        $q = Question::where('video_id', $request->video_id)->get();
        if(count($q)>4){
            return back();
        }
        return view('questions.create');
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
        $q = Question::where('video_id', $request->video_id)->get();
        if(count($q)>4){
            return back();
        }
        $data = $request->all();
        unset($data['_token']);
        unset($data['answers']);
        $rs = Question::insertGetId($data);
        if($rs) {
            if($request->answers) {
                foreach ($request->answers as $key => $answer) {
                    // if($answer) {
                        Answer::insert([
                            'question_id'=>$rs,
                            'answer'=>$answer,
                        ]);
                    // }
                }
            }
            return redirect(route('questions.index').'?video_id='.$request->video_id);
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
        $question = Question::find($id);
        $answer = Answer::where('question_id',$id)->get();
        return view('questions.edit', compact(['question','answer']));
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
        $que = Question::find($id);
        $video_id = $que->video_id;
        $question = $que->update([
                'question'=>$request->question
            ]);
        // $answer = Answer::where('question_id',$id)->delete();
        foreach ($request->answers as $key => $answer) {
            Answer::where('id', $request->answer_id[$key])->update([
                'answer'=>$answer
            ]);
        }
        return redirect(route('questions.index').'?video_id='.$video_id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $que = Question::find($id);
        $video_id = $que->video_id;
        Question::destroy($id);
        Answer::where('question_id',$id)->delete();
        return redirect(route('questions.index').'?video_id='.$video_id);
    }
}
