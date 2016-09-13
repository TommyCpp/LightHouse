<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Delegation extends Model
{
    protected $fillable=[
        "delegate_head_id","name","delegate_number","seat_number"
    ];

    public function head_delegate(){
        return $this->hasOne("App\\User","id","delegate_head_id");
    }

    public function delegates()
        //每个代表团有多个代表
    {
        return $this->hasMany("App\\Delegate","delegation_id","id");
    }
    
}
