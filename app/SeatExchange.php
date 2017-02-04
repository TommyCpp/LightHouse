<?php

namespace App;

use Barryvdh\Reflection\DocBlock\Type\Collection;
use Illuminate\Database\Eloquent\Model;

class SeatExchange extends Model
{
    public function seat_exchange_records()
    {
        return $this->hasMany("App\\SeatExchangeRecord", "request_id", "id");
    }
    
    public function setStatusAttribute($value)
    {
        $arr = [
            "pending" => 0,
            "success" => 1,
            "fail" => 2,
            "error" => 3
        ];
        $this->attributes['status'] = array_key_exists($value, $arr) ? $arr[$value] : -1;
    }

    public function getStatusAttribute($value)
    {
        $arr = [
            0 => "pending",
            1 => "success",
            2 => "fail",
            3 => "error"
        ];

        return array_key_exists($value, $arr) ? $arr[$value] : false;
    }

    /**
     * @return array
     * delta表示针对每个会场两个代表团之间的绝对差额，等于发起方实际收到的名额数量（如果是负数则表示发起方送出名额）
     */
    public function getDeltaAttribute()
    {
        $result = [];
        $committees = Committee::allInCache();
        foreach ($committees as $committee) {
            $record = $this->seat_exchange_records->where("committee_id", $committee->id);
            if ($record->count() != 0) {
                $record = $record->first();
                $result[$committee->abbreviation] = $record->in - $record->out;
            } else {
                $result[$committee->abbreviation] = 0;
            }
        }
        return $result;
    }

    /*
     * Service
     */
    public static function padding($initiator = null, $target = null)
    {
        $paddings = SeatExchange::where("status",0)->get();
        if($initiator){
            $paddings = $paddings->where("initiator",$initiator);
        }
        if($target){
            $paddings = $paddings->where("target",$target);
        }
        return $paddings;
    }
}
