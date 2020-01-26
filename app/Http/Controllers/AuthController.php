<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{

    public function register(Request $request)
    {
        $input = $request->all();
        $validationRules = [
            'username'   => 'required|string',
            'email'   => 'required|email|unique:users',
            'password'  => 'required|string|confirmed',
            'role' => 'required|in:admin,user',
        ];
        
        $validator = \Validator::make($input, $validationRules);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }
        
        $user = new User;
        $user->username     = $request->input('username');
        $user->email    = $request->input('email');
        $planPassword   = $request->input('password');
        $user->password = app('hash')->make($planPassword);
        $user->role     = $request->input('role');
        $user->save();
        return response()->json($user, 200);
    }

    public function login(Request $request)
    {
        $input = $request->all();
        $validationRules = [
            'email'   => 'required|string',
            'password'  => 'required|string',
        ];
        $validator = \Validator::make($input, $validationRules);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }
        $credentials = $request->only(['email', 'password']);
        if (! $token = Auth::attempt($credentials)) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }
        return response()->json([
            'token' => $token,
            'token_type' => 'bearer',
            'expires_in' => Auth::factory()->getTTL() * 60
        ], 200);
    }


}

