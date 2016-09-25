<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SeatExchange extends Model
{
    public function seat_exchange_records()
    {
        return $this->hasMany("App\\SeatExchangeRecord", "id", "request_id");
    }

    public function setStatusAttribute($value)
    {
        $arr = [
            "padding" => 0,
            "success" => 1,
            "fail" => 2,
            "error" => 3
        ];
        return array_key_exists($value, $arr) ? false : $arr[$value];
    }

    public function getStatusAttribute($value)
    {
        $arr = [
            0 => "padding",
            1 => "success",
            2 => "fail",
            3 => "error"
        ];

        return array_key_exists($value, $arr) ? false : $arr[$value];
    }
}
