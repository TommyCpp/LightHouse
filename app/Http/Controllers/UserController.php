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
        return view('user/user-management', compact('users'));
    }

    public function showUserManageForm(Request $request, $id)
    {
        $user = User::find($id);
        if ($user == null) {
            return redirect('user-management')->with('error', '该用户不存在');
        } else {
            return view('user/user-management-edit', ['user' => $user]);
        }
    }

    public function editUserInformation(Request $request, $id)
    {
        $user = User::find($id);
        if ($user == null) {
            return back()->withErrors("用户不存在");
        } else {
            $this->validate($request, [
                'name' => 'required',
                'first-name' => 'required|regex:/(^[A-Za-z]+$)+/',
                'last-name' => 'required|regex:/(^[A-Za-z]+$)+/',
                'high-school' => 'max:255',
                'Identity' => 'in:ADMIN,OT,AT,DEL,HEADDEL,DIR,COREDIR,VOL,OTHER,DAIS'
            ],[
                'required'=>':attribute 为必填项',
                'regex'=>':attribute 必须是英文字母',
                'in'=>':attribute 必须是下列值中的一个 :values'
            ]);
            //更新用户信息
            $user->name = $request->input('name');
            $user_archive = $user->archive;
            $user_archive->FirstName = $request->input('first-name');
            $user_archive->LastName = $request->input('last-name');
            $user_archive->HighSchool = $request->input('high-school');
            $user_archive->University = $request->input('university');
            $user_archive->Identity = implode(",",$request->input('identity'));
            if($user->save() && $user_archive->save())
                return response()->json();
            else
                return response()->json(['error'=>'保存数据时出现不可预知的错误'],422);
        }
    }
}
