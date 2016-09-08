<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Delegation extends Model
{
    protected $fillable=[
        "name","delegate","seat"
    ];
    
}
