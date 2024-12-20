<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;

class ApiAuthMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
       try {
            Log::info("--- API_AUTH middleware INIT ---");
            $token=$request->bearerToken();
            if(!$token)
            {
                Log::error("--- Authorization missed ---");
                return response()->json(["error"=>"Not authorized"],401);
            }
            $user=Auth::guard('api')->user();
            if (!$user) {
                Log::error("--- User not found ---");
                return response()->json(['error' => 'Unauthorized'], 401);
            }
            $request->merge(["user"=>$user]);
            Log::info("--- API_AUTH middleware SUCCESS ---");
            return $next($request);
       } catch (TokenExpiredException $e) {
            return response()->json(['error' => 'Token has expired'], 401);
        } catch (TokenInvalidException $e) {
            return response()->json(['error' => 'Token is invalid'], 401);
        } catch (JWTException $e) {
            return response()->json(['error' => 'Token is missing'], 401);
        }
    }
}
