<?php

namespace App\Http\Controllers;

use App\Committee;
use App\Delegation;
use App\Http\Requests\DelegationRequest;
use App\Http\Requests\SeatExchangeRequest;
use App\Seat;
use App\SeatExchange;
use App\SeatExchangeRecord;
use App\User;
use Auth;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\Input;

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

    public function showDelegationSeatExchangeRuleForm()
    {
        $committees = Committee::all();
        return view('delegation/rules', compact("committees"));
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

    public function showCommitteesLimitForm()
    {
        $committees = Committee::all();

        return view('delegation/limit', compact('committees'));
    }

    /**
     * @param Request $request
     * @return mixed
     * 各个委员会限额，该参数会在代表团自主进行交换时进行限制
     */
    public function updateCommitteeLimit(Request $request)
    {
        $committee_names = Committee::all('abbreviation')->pluck('abbreviation')->toArray();
        $rules = [];
        foreach ($committee_names as $committee_name) {
            $rules[$committee_name] = "required|integer|min:0";
        }
        $this->validate($request, $rules, [
            'required' => ':attribute 为必填项',
            'integer' => ':attribute 必须是数字',
            'min' => ':attribute 必须是正数'
        ]);

        //更新各个会场限额
        $committee_ids = Committee::all('id')->pluck('id')->toArray();
        foreach ($committee_ids as $committee_id) {
            $committee = Committee::find($committee_id);
            $committee->limit = $request->input($committee->abbreviation);
            $committee->save();
        }

        return redirect("committees/limit");
    }

    /**
     * @param DelegationRequest $request
     * @param $id
     * @return mixed
     * @middleware role:OT
     * 完成OT层面的代表团信息修改，主要包括
     * 1.修改领队信息
     * 2.修改代表团名称等
     * 3.修改席位信息（在此处添加或删除席位不受【每个代表团在各个会场席位数量上限】 的限制，便于OT进行奖励性分配
     *
     * 尚未实现日志记录功能
     */
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

    /*
     * 以上方法适用于OT登录的情况下，
     * 以下适用于代表团领队
     */

    public function showDelegationInformation(Request $request, $id)
    {
        $delegation = Delegation::find($id);
        $delegates = $delegation->delegates;
        $seat_collection = $delegation->seats;
        $committees = Committee::all("id", "abbreviation");
        $seats = [];
        foreach ($committees as $committee) {
            $seats[$committee->abbreviation] = $seat_collection->where("committee_id", $committee->id)->count();
        }

        return view("delegation/delegation", compact("seats", "committees", "delegation"));

    }

    public function showSeatExchange()
    {
        $committees = Committee::all();
        $committees_name = $committees->pluck("abbreviation");
        $delegations = Delegation::all();

        return view("delegation/seat-exchange", compact("committees", "committees_name", "delegations"));
    }

    public function SeatExchange(SeatExchangeRequest $request)
    {
        $committees = Committee::all();
        $committee_rules = $committees->pluck("limit");
        $committee_abbreviations = $committees->pluck("abbreviation");

        $initiator = Delegation::findOrFail(Auth::user()->delegation->id);
        $target = Delegation::findOrFail(Input::get("target"));

        $errors = [];
        
        
        //检查是否是一个已经存在的交换申请
        $is_corresponding = false;//是否存在一个申请，如果存在是否与正在处理的request中包含的信息相符
        $seat_exchanges = SeatExchange::all()->where("initiator", $initiator->id)->where("target", $target->id);
        if ($seat_exchanges->count() != 0) {
            //已经存在至少一个申请
            foreach ($seat_exchanges as $seat_exchange) {
                $is_corresponding = true;
                foreach ($committees as $committee) {
                    if ($seat_exchange->where("committee_id", $committee->id)->get("in") != $request->input($committee->abbreviation . "-in") || $seat_exchange->where("committee_id", $committee->id)->get("out") != $request->input($committee->abbreviation . "-out")) {
                        //数据库中记录与正在处理的request中的数据不符合
                        $is_corresponding = false;
                    }
                }
                if($is_corresponding)
                    break;
            }
            //如果没有一个申请与之相对应
            if(!$is_corresponding){
                //错误处理
            }
            else{
                //todo:进行名额交换
            }
        }

        //检查本代表团是否有足够名额
        $delegation_in_faults = [];//本代表团超限的会场
        $target_out_faults = [];//目标代表团名额不足的会场
        $target_in_faults = [];//目标代表团超限的会场
        $delegation_out_faults = [];//本代表团名额不足的会场
        foreach ($committees as $committee) {
            $current = $initiator->seats->where("committee_id", $committee->id)->count();
            $target_current = $target->seats->where("committee_id", $committee->id)->count();
            $in = $request->input($committee->abbreviation . "-in");
            $out = $request->input($committee->abbreviation . "-out");
            if ($current + $in > $committee->limit) {
                $delegation_in_faults[] = $committee->abbreviation;
            }
            if ($target_current < $current) {
                $target_out_faults[] = $committee->abbreviation;
            }
            if ($target_current + $out > $committee->limit) {
                $target_in_faults[] = $committee->abbreviation;
            }
            if ($current < $out) {
                $delegation_out_faults[] = $committee->abbreviation;
            }
        }
        
        //在SeatExchange中创建相应的数据项目
        $seat_exchange_request = new SeatExchange();
        $seat_exchange_request->initiator = $initiator->id;
        $seat_exchange_request->target = $target->id;
        $seat_exchange_request->save();
        
        $seat_exchange_records = [];
        foreach($committees as $committee){
            $seat_exchange_records[] = SeatExchangeRecord::create([
                'committee_id'=>$committee->id,
                'in'=>$request->input($committee->id."-in"),
                "out"=>$request->input($committee->id."-out")
            ]);
        }
        $seat_exchange_request->seat_exchange_records()->saveMany($seat_exchange_records);
        
        //todo:未测试代码，同时seats_exchange_records里对于in=0,out=0的会场也进行了存储，看看能不能减少存储量【关键在于修改后如何判断到底这个申请是否已经由目标方发送过】
        //todo:是否有必要保证两个代表团间同时只能进行一次交换
        

    }
    
    
    
    
    //helper functions
    private function exchangeSeats(SeatExchange $exchange_request)
    {
        //在完成验证之后处理席位交换的函数
        
    }


}
