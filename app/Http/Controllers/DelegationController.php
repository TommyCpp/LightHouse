<?php

namespace App\Http\Controllers;

use App\Committee;
use App\Delegation;
use App\User;
use Illuminate\Http\Request;

use App\Http\Requests;

class DelegationController extends Controller
{
    public function showCreateForm()
    {
        $committees = Committee::all();
        $users = User::all();
        $delegates = $users->filter(function (User $user) {
            return $user->hasRole('DEL');
        });
        $daises = $users->filter(function (User $user) {
            return $user->hasRole('DAIS');
        });

        return view('delegation/create-delegation', compact("committees", "delegates", "daises"));
    }

    public function create(Request $request)
    {
        $committees_double = Committee::all()->where('delegation', 2);//所有双代委员会
        $committees_names = Committee::all("abbreviation")->all();//所有委员会名称集合
        $names = [];
        for ($i = 0; $i < count($committees_names); $i++) {
            $names[$i] = $committees_names[$i]->abbreviation;
        }
        $names = implode(",",$names);
        $committees_validation = [];
        foreach ($committees_double as $committee) {
            $committees_validation[$committee->abbreviation] = "required|even";
        }
        $rules = [
            'name' => 'required',
            'delegate_number' => 'required|equal_to_total_seat:'.$names
        ];
        $rules = array_merge($rules, $committees_validation);
        $this->validate($request, $rules, [
            'required' => ':attribute 为必填项',
            'integer' => ':attribute 必须是数字',
            'in' => ':attribute 必须是下列值中的一个 :values',
            'even' => ':attribute 必须是一个偶数',
            'equal_to_total_seat' => '代表团人数与席位总数不符合'
        ]);

        //创建代表团
        $delegation = new Delegation();
        $delegation->head_delegate_id = $request->input("head_delegate_id");
        $delegation->name = $request->input("name");
        $delegation->delegate_number = $request->input("delegate_number");
        $delegation->seat_number = $request->input("delegate_number");
        $delegation->save();

        //创建相应代表
        //TODO:如果领队是代表，如果领队不是代表

    }
}
