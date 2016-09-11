<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Delegation extends Model
{
    protected $fillable=[
        "delegate_head_id","name","delegate_number","seat_number"
    ];
    
}
