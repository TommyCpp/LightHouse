<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Delegate extends Model
{
    public $primaryKey = "delegate_id";
    
    protected $fillable=[
        'delegate_id','delegation_id','seat_id'
    ];
}
