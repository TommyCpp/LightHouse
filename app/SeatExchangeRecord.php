<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SeatExchangeRecord extends Model
{
    protected $fillable=["committee_id","in","out"];
    public $timestamps = false;

    public function seat_exchange()
    {
        return $this->belongsTo("App\\SeatExchange","request_id","id");
    }
    
}
