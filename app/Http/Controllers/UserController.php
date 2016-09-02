<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;

use App\Http\Requests;
use Redirect;

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

    public function showUserManageForm(Request $request,$id){
        $user = User::find($id);
        if($user == null){
            return redirect('user-management')->with('error','该用户不存在');
        }
        else{
            return view('user/user-management-edit',['user'=>$user]);
        }
    }
    
    public function editUserInformation(Request $request){
        
    }
}
