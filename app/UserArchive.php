<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserArchive extends Model
{
    protected $fillable = [
        'id',
        'FirstName',
        'LastName',
        'HighSchool',
        'University',
        'Identity'
    ];
    
    public function user()
    {
        return $this->belongsTo('App\User');
    }
}
