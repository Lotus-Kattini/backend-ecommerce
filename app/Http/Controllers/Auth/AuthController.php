<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(Request $request){
        $request->validate([
            'name'=>'required|max:255|string',
            'email'=>'required|max:255|string|email|unique:users,email',
            'password'=>'required|min:8|string|confirmed',
            'role'=>'string|nullable'
        ]);
        $role=$request->role == 'admin' ? 'admin' : 'user';
        $user=User::create([
            'name'=>$request->name,
            'email'=>$request->email,
            'password'=>Hash::make($request->password),
            'role'=>$role,
        ]);
        $token=Auth::login($user);
        return response()->json([
            'message'=>'Registered Successfully',
            'user'=>$user,
            'token'=>$token
        ],200);
    }


    public function login(Request $request){
        $request->validate([
            'email'=>'required|string|email',
            'password'=>'required|string'
        ]);
        $credentails=$request->only('email','password');
        $token=Auth::attempt($credentails);
        if($token)
        {
            $user=Auth::user();
            return response()->json([
                'message'=>'loged in Successfully',
                'user'=>$user,
                'token'=>$token
            ],200);
        }else{
            return response()->json([
            'message'=>' wrong email or password',
        ],401);
        }
    }



    public function logout()  {
        Auth::logout();
        return response()->json([
            'message'=>' logout successfully',
        ],200);
    }


    public function refresh(){
        return response()->json([
            'user'=>Auth::user(),
            'token'=>Auth::refresh(),
        ],200);
    }




}
