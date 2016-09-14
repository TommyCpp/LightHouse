<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Seat extends Model
{
    public $primaryKey = "seat_id";

    protected $fillable=[
        'committee_id','main_name','assist_name','note','delegation_id','is_distributed'
    ];

    public function committee(){
        return $this->belongsTo("App\\Committee","committee_id","id");
    }

    public function delegation()
    {
        return $this->belongsTo("App\\Delegation","delegation_id","id");
    }
}
