<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Delegation extends Model
{
    protected $fillable=[
        "head_delegate_id","name","delegate_number","seat_number"
    ];

    public function head_delegate(){
        return $this->belongsTo("App\\User","head_delegate_id","id");
    }

    public function delegates()
        //每个代表团有多个代表
    {
        return $this->hasMany("App\\Delegate","delegation_id","id");
    }

    public function seats()
    {//每个代表团有多个席位
        return $this->hasMany("App\\Seat","delegation_id","id");        
    }

    public function getHeadDelegationNameAttribute()
    {
        return $this->head_delegate->name;
    }
    
    public function getCommitteeSeatsAttribute(){
        //返回 会场缩写 => 席位 关联数组
        $committees = Committee::all();
        $result = [];
        foreach($committees as $committee){
            $committee_seat = [];
            $committee_seat['committee']=$committee->abbreviation;
            $committee_seat['seats']=$this->seats()->where("committee_id",$committee->id)->count();
            $result[count($result)] = $committee_seat;
        }
        return $result;
    }
}
