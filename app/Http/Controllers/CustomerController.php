<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Customer;
class CustomerController extends Controller
{
    public function createcustomer(Request $request){
        try{
            \Log::info('Update request data', $request->all());
            $validatedData = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:users',
                'phone' => 'required|string|',
                'address' => 'required|string',
                'cnic' => 'required|string'
            ]);
           
            $customer =  new Customer([
                'name' => $validatedData['name'],
                'email' => $validatedData['email'],
                'phone' => $validatedData['phone'],
                'address' => $validatedData['address'], 
                'cnic' => $validatedData['cnic'], 
            ]);
            $customer->save();
            return response()->json([
                'success' => true,
                'message' => "Customer added successfully"
            ], 200);
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
}
