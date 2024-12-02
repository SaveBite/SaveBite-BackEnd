<?php

namespace App\Http\Services\Dashboard\Auth;

use App\Http\Requests\Dashboard\Auth\LoginRequest;
use App\Models\Manager;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthService
{
    public function login(LoginRequest $request){
        $credentials = $request->validated();
        $rememberMe = $request->remember_me == 'on';
        if (auth()->attempt($credentials, $rememberMe)) {
            return redirect()->route('/');
        } else {
            return redirect()->route('auth.login')->with(['error' => __('messages.Incorrect email or password')]);
        }
    }

    public function logout(){
        auth()->logout();
        return redirect()->route('auth.login');
    }
    public function updatePassword($request){
        return $request;
        $user=Manager::findorfail($request->id);
        if(!Hash::check($request->old_password,$user->password)){
            return back()->with('error' , __('messages.Old_Password_Wrong'));
        }else{
            $user->update(['password'=>$request->new_password]);
            return back()->with('success' , __('Updated Successfully'));
        }
    }
}
