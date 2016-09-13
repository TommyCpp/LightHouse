<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Delegate extends Model
{
    public $primaryKey = "delegate_id";
    
    protected $fillable=[
        'delegate_id','delegation_id','seat_id'
    ];
    
    public function delegation(){
        //每个代表只有一个代表团
        return $this->belongsTo("App\\Delegation","id","delegation_id");
            
    }
}
