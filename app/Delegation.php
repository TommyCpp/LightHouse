<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Delegation extends Model
{
    protected $fillable=[
        "delegate_head_id","name","delegate_number","seat_number"
    ];

    public function head_delegate(){
        return $this->hasOne("App\\Delegate","delegate_id","delegate_head_id");
    }
    
    
    
}
