<?php

namespace App\Http\Controllers;

use App\Http\Requests\AuthLoginRequest;
use App\Http\Requests\AuthRegisterRequest;
use App\Http\Requests\VerifyPhoneRequest;
use App\Jobs\CreatePhoneMessageJob;
use App\Jobs\CreateWalletJob;
use App\Models\PhoneMessage;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    public function login(AuthLoginRequest $request)  {
        $validated=$request->validated();
        $user=User::where("email",$validated['identifier'])->orWhere("phoneNumber",$validated['identifier'])->where("active",true)->first();
        if(!$user)
        {
            return response()->json(['error' => 'Not found'], 404);
        }
        $token = Auth::guard('api')->attempt(["email"=>$user->email,"password"=>$validated['password']]);
        if (!$token) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        return response()->json(["token"=>$token],200);
    }

    public function register(AuthRegisterRequest $request)  {
        $validated=$request->validated();
        $validated['password']=Hash::make($validated['password']);
        $user=User::create($validated);
        CreatePhoneMessageJob::dispatch($user);
        CreateWalletJob::dispatch($user);
        $token=JWTAuth::fromUser($user);
        return response()->json(["token"=>$token],201);
    }

    public function verifyPhone(VerifyPhoneRequest $request)
    {
        $validated=$request->validated();
        $phoneMessage=PhoneMessage::with("users")->where("phoneNumber", $validated['phoneNumber'])
        ->where("otpCode", $validated['otpCode'])
        ->first();
        if(!$phoneMessage)
        {
            return response()->json(["error"=>"Incorrect"],400);
        }
        return response()->json(['message'=>"success"],200);
    }
}
