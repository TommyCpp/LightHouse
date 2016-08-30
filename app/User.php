<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Http\Request;

class User extends Authenticatable
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * Where User has corresponding role
     * @param $role
     * @return bool
     */
    public function hasRole($role){
        return !(strpos($this->archive->Identity,$role) === false);
    }

    public function archive(){
        return $this->hasOne('App\UserArchive','id');
    }

}
