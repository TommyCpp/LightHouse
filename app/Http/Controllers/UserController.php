<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;

use App\Http\Requests;

class UserController extends Controller
{
    /**
     * @param Request $request
     */
    public function userManage(Request $request)
    {
        $users = User::all();
        return view('user/user-management',compact('users'));
    }
}
