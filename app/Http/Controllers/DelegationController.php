<?php

namespace App\Http\Controllers;

use App\Committee;
use App\Delegation;
use App\Seat;
use App\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;

use App\Http\Requests;

class DelegationController extends Controller
{
    public function showCreateForm()
    {
        $committees = Committee::all();
        $users = User::all();
        $delegates = $users->filter(function (User $user) {
            return $user->hasRole('HEADDEL');
        });

        return view('delegation/create-delegation', compact("committees", "delegates"));
    }

    public function showDelegations()
    {
        $committee_names = Committee::all("abbreviation");
        $names = new Collection();
        foreach ($committee_names as $committee_name) {
            $names->add($committee_name->abbreviation);
        }
        return view("delegation/delegations", ["delegations" => Delegation::all(), 'committee_names' => $names->toArray()]);
    }

    public function delete(Request $request, $id)
    {
        $delegation = Delegation::find($id);
        $seats = $delegation->seats;
        foreach ($seats as $seat) {
            $seat->is_distributed = false;
            $seat->delegation_id = null;
            $seat->save();
        }//释放席位
        $status = $delegation->delete();
        return $status ? response("", 200) : response("", 404);

    }

    public function create(Request $request)
    {
        $committees_double = Committee::all()->where('delegation', 2);//所有双代委员会
        $committees = Committee::all()->all();//所有委员会名称集合
        $names = [];
        for ($i = 0; $i < count($committees); $i++) {
            $names[$i] = $committees[$i]->abbreviation;
        }
        $names = implode(",", $names);

        $committees_validation = [];
        foreach ($committees_double as $committee) {
            $committees_validation[$committee->abbreviation] = "required|even";
        }

        $rules = [
            'name' => 'required',
            'delegate_number' => 'required|equal_to_total_seat:' . $names . '|min:1'
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
        $user = User::find($request->input("head_delegate_id"));
        $delegation->head_delegate()->associate($user);
        $delegation->name = $request->input("name");
        $delegation->delegate_number = $request->input("delegate_number");
        $delegation->seat_number = $request->input("delegate_number");
        $delegation->save();

        //创建相应代表
        //不论领队是否代表都创建新用户，在填写代表信息页面将其席位进行转换
        $delegation_id = $delegation->id;

//        $delegates = [];
        for ($i = 0; $i < count($committees); $i++) {
            $committee_id = $committees[$i]->id;
            $committee_abbr = $committees[$i]->abbreviation;
            $seats = Seat::all()->where("committee_id", $committee_id)->where("is_distributed", 0)->take($request->input($committee_abbr));
            if ($seats->count() != $request->input($committee_abbr)) {
                //如果剩余席位不够分配
                //回滚
                $delegation->delete();
                $error = new Collection();
                $error->add($committees[$i]->chinese_name . "席位不足");
                return redirect('create-delegation')->with("errors", $error);
            }
            foreach ($seats as $seat) {
                $seat->is_distributed = true;
                $seat->update();
            }
            $delegation->seats()->saveMany($seats);
        }
        return redirect("delegations");
    }


}
