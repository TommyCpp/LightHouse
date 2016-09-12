<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Seat extends Model
{
    public $primaryKey = "seat_id";

    protected $fillable=[
        'committee_id','main_name','assist_name','note'
    ];

    public function committee(){
        return $this->belongsTo("App\\Committee","id","committee_id");
    }
}
