<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function register(Request $request)
    {

        $validator = Validator::make($request->all(), [
    		'name' => 'required|string|max:255',
    		'email' => 'required|string|email|max:255|unique:users',
    		'password' => 'required|string|min:8|confirmed',
    		'password_confirmation' => 'required|string',
            'role' => 'required|integer',
		]);

        if ($validator->fails()) {
        	return response()->json([
        		'success' => false,
        		'message' => $validator->errors()->all(),
        	], 401);
        }
        
        $user = User::create($request);

        $accessToken = $user->createToken($user->id)->accessToken;

        return response()->json(['access_token' => $accessToken, 'userId' => $user->id], 200);
    }

    public function login(Request $request)
    {
        $loginData = $request->validate([
            'email' => 'required|email|string',
            'password' => 'required|string'
        ]);

        $credentials = $request->only('email', 'password');
        
        if (Auth::attempt($credentials)) {
            $userId = Auth::id();
            $user = Auth::user();
            $accessToken = $user->createToken($userId)->accessToken;
            return response()->json(['user' => $user, 'access_token' => $accessToken], 200);
        }

        return response()->json(['message' => 'Unathorized user.'],401);
       

    }

    
    public function logout(Request $request) {
    	$request->user()->token()->revoke();
    	return response()->json(['message' => 'logout success'], 200);
    }
}
