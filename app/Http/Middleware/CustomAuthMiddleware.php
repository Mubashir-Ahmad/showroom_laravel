<?php


namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CustomAuthMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        try {
            if (Auth::guard('api')->check()) {
                $user = Auth::guard('api')->user();
                if (!$user) {
                    return response()->json([
                        "success" => false,
                        'message' => 'Please login to access information'
                    ], 401);
                }
                $request->merge(['user' => $user]);
                return $next($request);
            } else {
                return response()->json([
                    "success" => false,
                    'message' => 'Please login to access information'
                ], 401);
            }
        } catch (\Illuminate\Validation\ValidationException $e) {
            // If validation fails, get the error messages
            $errorMessages = $e->validator->errors();
    
            return response()->json([
                "success" => false,
                'message' => 'Validation error',
                'errors' => $errorMessages->messages()
            ], 400);
        }
    }
    
}