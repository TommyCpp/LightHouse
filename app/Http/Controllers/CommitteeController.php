<?php

namespace App\Http\Controllers;

use App\Committee;
use App\Delegation;
use App\Seat;
use DB;
use Faker\Provider\lv_LV\Person;
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
        $this->middleware('role:AT', ['only' => ['showCreateForm', 'showUpdateForm', 'update', 'create', 'delete']]);
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
            'delegation' => 'required|in:1,2,0',
            'language' => 'required|in:chinese,english',
            'number' => 'integer|required',
            'topic_chinese_name' => 'required',
            'topic_english_name' => 'required',
            'abbreviation' => 'required'
        ], [
            'required' => ':attribute 为必填项',
            'integer' => ':attribute 必须是数字',
            'in' => ':attribute 必须是下列值中的一个 :values'
        ]);

        Committee::create($request->input());
        $committee = Committee::find($request->input("id"));
        $seats = [];
        DB::beginTransaction();
        for ($i = 0; $i < $request->input("number"); $i++) {
            $seats[count($seats)] = new Seat();
        }
        $committee->seats()->saveMany($seats);
        DB::commit();
        return redirect('committees');
    }

    public function delete(Request $request, $id)
    {
        $committee = Committee::find($id);
        DB::beginTransaction();
        foreach ($committee->seats as $seat) {
            $seat->delete();
        }
        $status = $committee->delete();
        DB::commit();//事务处理
        return $status ? response("", 200) : response("", 500);
    }

    public function showUpdateForm($id)
    {
        $committee = Committee::all()->find($id);
        return view('committee/committee')->with("committee", $committee);
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'id' => 'required',
            'chinese_name' => 'required',
            'english_name' => 'required',
            'delegation' => 'required|in:1,2,0',
            'language' => 'required|in:chinese,english',
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
        DB::beginTransaction();
        if ($id != $request->input('id')) {
            //如果更改ID的话,更新所有seats的id
            $seats = $committee->seats->all();
            foreach ($seats as $seat) {
                $seat->update(["committee_id" => $request->input("id")]);
            }
        }
        DB::commit();
        if ($committee->update($request->input())) {
            return redirect("committees");
        } else {
            return response()->back();
        }

    }

    public function getNote(Request $request, $id)
    {
        if ($request->ajax()) {
            return response(Committee::find($id)->note);
        } else {
            return response("", 401);//401表示未授权
        }
    }

    public function getSeats(Request $request, $id)
    {
//        if ($request->ajax()) {
            $delegations = Delegation::all("id","name");
            $result = [];
            $seats = Seat::all()->where("committee_id",(int)$id);
            foreach ($delegations as $delegation) {
                $result[] = [$delegation->id,$delegation->name,$seats->where("delegation_id", $delegation->id)->count()];
            }
            return response()->json($result);
//        } else {
//            return response("", 401);
//        }
    }
}
