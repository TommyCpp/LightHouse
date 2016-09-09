<?php

namespace App\Http\Controllers;

use App\Committee;
use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\Input;

class CommitteeController extends Controller
{
    /**
     *
     */

    public function __construct()
    {
        $this->middleware('role:AT', ['only' => ['showCreateForm', 'create','delete']]);
    }

    public function index()
    {
        $committees = Committee::all();
        return view('committee/index', compact('committees'));
    }

    public function showCreateForm(Request $request)
    {
        return view('committee/create-committee');
    }

    public function create(Request $request)
    {
        $this->validate($request, [
            'id' => 'required',
            'chinese_name' => 'required',
            'english_name' => 'required',
            'delegation' => 'in:1,2,3',
            'number' => 'integer|required',
            'topic_chinese_name' => 'required',
            'topic_english_name' => 'required',
            'abbreviation' => 'required'
        ], [
            'required' => ':attribute 为必填项',
            'integer' => ':attribute 必须是数字',
            'in' => ':attribute 必须是下列值中的一个 :values'
        ]);

        $committee = Committee::create($request->input());

        return redirect('committees');
    }

    public function delete(Request $request,$id)
    {
        if(Committee::all()->find($id)->delete()){
            return response();
        }
        else{
            return response("",404);
        }
    }

    public function showUpdateForm($id)
    {
        $committee = Committee::all()->find($id);
        return view('committee/committee')->with("committee",$committee);
    }

    public function update(Request $request,$id)
    {
        $this->validate($request, [
            'id' => 'required',
            'chinese_name' => 'required',
            'english_name' => 'required',
            'delegation' => 'in:1,2,3',
            'number' => 'integer|required',
            'topic_chinese_name' => 'required',
            'topic_english_name' => 'required',
            'abbreviation' => 'required'
        ], [
            'required' => ':attribute 为必填项',
            'integer' => ':attribute 必须是数字',
            'in' => ':attribute 必须是下列值中的一个 :values'
        ]);

        $committee = Committee::all()->find($id);
        if($committee->update($request->input())){
            return redirect("committees");
        }
        else{
            return response()->back();
        }

    }
}
