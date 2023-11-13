<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Brandname;
use Illuminate\Support\Facades\Log;
class brandController extends Controller
{
    public function addbrand(Request $request)
    {
        try{
            $user = $request->input('user');
            if($user)
            {
                $validateData = $request->validate([
                    'name' => 'required|string',
                    'logo' => 'required|image',
                ]);
                if ($request->hasFile('logo')) {
                    // $imageName = Str::random(32) . "." . $request->file('logo')->getClientOriginalExtension();
    
                    $imagePath = $request->file('logo');

                    $imageName = Str::random(32) . '.' . $imagePath->getClientOriginalExtension();
    
                    $imagePath->storeAs('public', $imageName);
                $brand =  new Brandname([
                    'name' => $validateData['name'],
                    'user_id' => $user->id,
                    'logo'=> $imageName
                ]);
                $brand->save();
                return response()->json([
                    'success'=>true,
                    'message'=>"Beand name save successfully"
                ],200);
            }   else {
                return response()->json([
                    'success' => false,
                    'message' => "Image not provided"
                ], 400);
            }
            
        }
    }
        catch (\Illuminate\Validation\ValidationException $e) {
            // If validation fails, get the error messages
            $errorMessages = $e->validator->errors();

            return response()->json([
                "success" => false,
                'message' => 'Validation error',
                'errors' => $errorMessages->messages()
            ], 400);
        }
    }
    public function allbrand(){
        $brand = Brandname::getAllbrand();
        return response()->json([
            "success" => true,
            'message' => $brand
        ], 200);
    }
}
