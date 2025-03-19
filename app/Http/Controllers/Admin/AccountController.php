<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AccountController extends Controller
{
    //login
    public function login()
    {
        return view('admin.account.login');
    }

    //loginSubmit
    public function loginSubmit(Request $request)
    {
        if(User::where("email", $request->email)->where("password", $request->password)->exists()){
            $user = User::where("email", $request->email)->first();
            Auth::login($user);

            return redirect(route('admin.dashboard.index'));
        }else{
            return redirect(route('login'))->with('error', 'Your email/password is wrong!');
        }
    }

    public function logoutSubmit(){
        Auth::logout();
    }
}
