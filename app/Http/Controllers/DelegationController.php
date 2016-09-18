<?php

namespace App\Http\Controllers;

use App\Committee;
use App\Delegation;
use App\Http\Requests\DelegationRequest;
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
    public function showDelegationSeatExchangeRuleForm(){
        $committees  = Committee::all();
        return view('delegation/rules',compact("committees"));
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

    public function showUpdateForm(Request $request, $id)
    {
        $delegation = Delegation::find($id);
        $committee_seats = $delegation->committee_seats;
        $users = User::all();
        $delegates = $users->filter(function (User $user) {
            return $user->hasRole('HEADDEL');
        });
        return view('delegation/edit', compact("delegation", "committee_seats", "delegates"));
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

    public function edit(DelegationRequest $request, $id)
    {
        $committees = Committee::all();


        $delegation = Delegation::all()->find($id);
        if ($delegation->head_delegate->id != $request->input("head_delegate_id")) {
            //更改领队
            $delegation->head_delegate()->dissociate();
            $delegation->head_delegate()->associate(User::find($request->input("head_delegate_id")));
        }
        $delegation->name = $request->input("name");
        $delegation->delegate_number = $request->input("delegate_number");
        $delegation->seat_number = $request->input("delegate_number");

        //修改会场
        for ($i = 0; $i < count($committees); $i++) {
            $committee_id = $committees[$i]->id;
            $committee_abbr = $committees[$i]->abbreviation;
            $current_seat = $delegation->seats->where("committee_id", $committee_id);
            $difference = $current_seat->count() - $request->input($committee_abbr);
            if ($difference > 0) {
                //需要从代表团中删除席位
                foreach ($current_seat->take($difference) as $seat) {
                    $seat->delegation()->dissociate();//删除的席位将回到席位池中
                    $seat->is_distributed = false;
                    $seat->save();
                }
            } else if ($difference < 0) {
                //需要增加席位
                $difference = -$difference;
                $seats = Seat::where("committee_id", $committee_id)->where("is_distributed", 0)->take($difference)->get();
                if ($seats->count() < $difference) {
                    //如果剩余席位不够分配
                    //回滚
                    $error = new Collection();
                    $error->add($committees[$i]->chinese_name . "席位不足");
                    return redirect('/delegation/' . $committee_id . '/edit')->with("errors", $error);
                }
                foreach ($seats as $seat) {
                    $seat->is_distributed = true;
                    $seat->save();
                }
                $delegation->seats()->saveMany($seats);
            }
        }
        $delegation->save();

        return redirect("/delegations");
    }

    public function create(DelegationRequest $request)
    {
        $committees = Committee::all();

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
