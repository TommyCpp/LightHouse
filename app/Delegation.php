<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\SeatExchange;

class Delegation extends Model
{
    protected $fillable = [
        "head_delegate_id", "name", "delegate_number", "seat_number"
    ];

    public function head_delegate()
    {
        return $this->belongsTo("App\\User", "head_delegate_id", "id");
    }

    public function delegates()
        //每个代表团有多个代表
    {
        return $this->hasMany("App\\Delegate", "delegation_id", "id");
    }

    public function seats()
    {//每个代表团有多个席位
        return $this->hasMany("App\\Seat", "delegation_id", "id");
    }

    public function getHeadDelegationNameAttribute()
    {
        return $this->head_delegate->name;
    }

    public function getCommitteeSeatsAttribute()
    {
        //返回 会场缩写 => 席位 关联数组
        $committees = Committee::allInCache();
        $result = [];
        foreach ($committees as $committee) {
            $result[$committee->abbreviation] = $this->seats()->where("committee_id", $committee->id)->count();
        }
        return $result;
    }


    protected static function boot()
    {
        parent::boot(); // TODO: Change the autogenerated stub
        static::deleting(function ($delegation) {
            foreach ($delegation->seats as $seat) {
                $seat->is_distributed = false;
                $seat->delegation_id = null;
                $seat->save();
            }//释放席位
            $exchange_requests = SeatExchange::where("initiator", $delegation->id)->orWhere("target", $delegation->id)->get()->where("status", "pending");
            foreach ($exchange_requests as $exchange_request) {
                $exchange_request->status = "fail";
                $exchange_request->save();
            }//修改所有与被删除代表团相关的seat_exchange为fail

        });
    }
}
