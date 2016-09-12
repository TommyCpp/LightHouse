<?php

namespace App\Http\Controllers;

use App\Committee;
use App\User;
use Illuminate\Http\Request;

use App\Http\Requests;

class DelegationController extends Controller
{
    public function showCreateForm(){
        $committees = Committee::all();
        $users = User::all();
        $delegates = $users->filter(function(User $user){
            return $user->hasRole('DEL');
        });
        $daises =  $users->filter(function(User $user){
            return $user->hasRole('DAIS');
        });
        
        return view('delegation/create-delegation',compact("committees","delegates","daises"));
    }
}
