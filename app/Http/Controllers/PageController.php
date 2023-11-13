<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class PageController extends Controller
{
    public function store(Request $request)
    {
        try{
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'number' => 'required|numeric',
            'address' => 'required|string',
            'password' => 'required|string'
        ]);

        DB::table('users')->insert([
            'name' => $validatedData['name'],
            'email' => $validatedData['email'],
            'number' => $validatedData['number'],
            'address' => $validatedData['address'],
            'password' => bcrypt($validatedData['password']),
        ]);

        return response()->json([
            "success"=>true,
            'message' => 'User created successfully'], 200);
        }
        catch (\Illuminate\Validation\ValidationException $e) {
            // If validation fails, get the error messages
            $errorMessages = $e->validator->errors();
    
            return response()->json([
                "success" => false,
                'message' => 'Validation error',
                'errors' => $errorMessages
            ], 400); // Use a 400 status code for validation error
        }
        
    }
    public function show(){
        $user = DB::table('users')->get();
        return response()->json([
            "success"=>true,
            'message' => $user], 200);
    }
    public function singleuser(){
        try{
            if(Auth::guard('api')->check()){
                $user = Auth::guard('api')->user();
                $users = DB::table('users')->find($user->id);
               
                if(!$user){
                    return response()->json([
                        "success" => false,
                        'message' => 'Please login to access information'
                    ], 400);
                }
                return response()->json([
                    "success" => true,
                    'message' => $users
                ], 200);
            }
            else{
                return response()->json([
                    "success" => false,
                    'message' => 'Please login to access information'
                ], 400);
            }
        }
        catch (\Illuminate\Validation\ValidationException $e) {
            // If validation fails, get the error messages
            $errorMessages = $e->validator->errors();
    
            return response()->json([
                "success" => false,
                'message' => 'Validation error',
                'errors' => $errorMessages->messages()
            ], 400); // Use a 400 status code for validation error
        }
    }
    public function update (Request $request, $id)
{       
    try {
        $user = $request->input('user'); // Access the $user variable set by the middleware
            if ($user) {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id, // Ensure unique email except for the current user
            'number' => 'required|numeric',
            'address' => 'required|string',
        ]);

        // Find the user by ID
        $user = DB::table('users')->find($id);

        if (!$user) {
            return response()->json([
                "success" => false,
                'message' => 'User not found'
            ], 404); // User not found, return a 404 status code
        }

        // Update the user information
        DB::table('users')->where('id', $id)->update([
            'name' => $validatedData['name'],
            'email' => $validatedData['email'],
            'number' => $validatedData['number'],
            'address' => $validatedData['address'],
            
        ]);

        return response()->json([
            "success" => true,
            'message' => 'User information updated successfully'
        ], 200);
    }
    } catch (\Illuminate\Validation\ValidationException $e) {
        // If validation fails, get the error messages
        $errorMessages = $e->validator->errors();

        return response()->json([
            "success" => false,
            'message' => 'Validation error',
            'errors' => $errorMessages
        ], 400); // Use a 400 status code for validation error
    }
}
    public function login (Request $request){
        try{
            $request->validate([
                'email'=>'required|email',
                'password' => 'required',
            ]);
            if (Auth::attempt($request->only('email', 'password'))) {
                $user = Auth::user();
                $token = $user->createToken('authToken')->plainTextToken;
                return response()->json([
                    'success'=>true,
                    'token' => $token,
                    'user' => $user, // Include user information in the response
                ], 200);
            }
            else{
                return response()->json([
                    'success'=>false,
                    'messege' => 'Email or password not correct', // Include user information in the response
                ], 400);
            }
        }
        catch (err) {
            // If validation fails, get the error messages
            return response()->json([
                "success" => false,
                'message' => 'Validation error',
                'errors' => $err
            ], 400); // Use a 400 status code for validation error
        }
    }

}
