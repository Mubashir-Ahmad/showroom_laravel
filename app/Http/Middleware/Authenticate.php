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

// public function handle($request, Closure $next)
// {
//     try {
//         $token = JWTAuth::parseToken()->authenticate();

//         if (!$token) {
//             return response()->json(['message' => 'Please log in to access this resource'], 401);
//         }

//         $user = Auth::user(); // The authenticated user

//         $request->merge(['user' => $user]);

//         return $next($request);
//     } catch (TokenInvalidException $e) {
//         return response()->json(['message' => 'Invalid token'], 401);
//     } catch (\Exception $e) {
//         return response()->json(['message' => 'Unauthorized'], 401);
//     }
// }

// public function handle($request, Closure $next)
// {
//     try {
//         $token = JWTAuth::parseToken()->authenticate();

//         if (!$token) {
//             return response()->json(['message' => 'Please log in to access this resource'], 401);
//         }

//         $user = Auth::user(); // The authenticated user

//         $request->merge(['user' => $user]);

//         return $next($request);
//     } catch (TokenInvalidException $e) {
//         return response()->json(['message' => 'Invalid token'], 401);
//     } catch (\Exception $e) {
//         return response()->json(['message' => 'Unauthorized'], 401);
//     }
// }
