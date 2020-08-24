<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\User;


class AuthController extends Controller
{
    public function login(Request $request)
    {

        if( $user = User::where('email', $request->username)->first() )
        {   
            if( Hash::check( $request->password, $user->password) )
            {
                return response()->json([
                    'message' => 'Welcome '. $user->name,
                    'access_token' => $user->api_token
                ], 200);
            }
        }

        return response()->json([
            'message' => 'User not found!',
            'access_token' => null,
        ], 401);
    }

    public function logout()
    {
        // \Auth::logout();
        return response()->json([
            'message' => 'logout'
        ], 200);
    }

}
