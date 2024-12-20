<?php

namespace App\Http\Controllers;

use App\Http\Requests\AuthLoginRequest;
use App\Http\Requests\AuthRegisterRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    public function login(AuthLoginRequest $request)  {
        $validated=$request->validated();
        $token = Auth::guard('api')->attempt($validated);
        if (!$token) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return response()->json(["token"=>$token],200);

    }

    public function register(AuthRegisterRequest $request)  {
        $validated=$request->validated();
        $validated['password']=Hash::make($validated['password']);
        $user=User::create($validated);

        $token=JWTAuth::fromUser($user);

        return response()->json(["token"=>$token],201);

    }
}
