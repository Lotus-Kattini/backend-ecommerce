<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(){
        $users=User::paginate(10);
        return response()->json([
            'users'=>$users,
        ],200);
    }


    public function show($id){
        $user=User::find($id);
        if($user){
            return response()->json([
                'users'=>$user,
            ],200);
        }else{
            return response()->json([
                'message'=>'user is not found',
            ],404);
        }
    }


        public function destroy($id){
        $user=User::find($id);
        if($user){
            $user->delete();
            return response()->json([
                'message'=>'user deleted successfuly',
            ],200);
        }else{
            return response()->json([
                'message'=>'user is not found',
            ],404);
        }
    }



}
