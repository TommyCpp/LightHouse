<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SeatExchangeRecord extends Model
{
    protected $fillable=["committee_id","in","out","request_id"];

    public function seat_exchange()
    {
        return $this->belongsTo("App\\SeatExchange","id","request_id");
    }
    
}
