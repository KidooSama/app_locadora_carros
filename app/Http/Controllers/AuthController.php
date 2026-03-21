<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function login(Request $request){
        
        $cred = $request->all(['email','password']);
        $token = auth('api')->attempt($cred);

        if ($token) {
            return response()->json(['token'=> $token]);    
        }else{
            return response()->json(['erro'=> 'Usuário Inválido'], 403);
        }
    }
    public function logout(){
        auth('api')->logout();
        return response()->json(['mesage'=> 'Usuario foi deslogado']);
    }
    
    public function refresh(){
    /** @var \Tymon\JWTAuth\JWTGuard  */
        $auth = auth('api');
        $token = $auth->refresh();
        return response()->json(['token'=> $token]);
    }

    public function me(){
        return response()->json(['data'=>auth()->user()]);
    }
    
}
