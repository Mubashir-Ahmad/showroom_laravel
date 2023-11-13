<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Vehicle;
use App\Models\Brandname;
use Illuminate\Support\Facades\Log;
class VehicleController extends Controller
{
    public function postvehicle(Request $request)
    {
        try {
            $user = $request->input('user'); // Access the $user variable set by the middleware
            if ($user) {
                Log::info('Request information', ['request' => $request->all()]);
                $validateData = $request->validate([
                    'brandname' => 'required|string|max:20',
                    'color' => 'required|string|max:10',
                    'document' => 'string|max:10',
                    'description' => 'required|string|max:150',
                    'model' => 'required|numeric',
                    'varient' => 'required|string',
                    'chasie_number' => 'required|string',
                    'engine_number' => 'required|string',
                    'purchase_price' => 'required|numeric',
                    'sale_price' => 'required|numeric',
                    'image_path' => 'required|image', // Validated image
                ]);
    
                // Handle image upload correctly
                if ($request->hasFile('image_path')) {
                    // $imageName = Str::random(32) . "." . $request->file('image_path')->getClientOriginalExtension();
    
                    $imagePath = $request->file('image_path');

                    $imageName = Str::random(32) . '.' . $imagePath->getClientOriginalExtension();
    
                    $imagePath->storeAs('public', $imageName);
                    $brandName = $request->input('brandname');
                    // Find or create the brand
                    $brand = Brandname::firstOrCreate(['name' => $brandName]);
                    $brandid = $brand->id;
                    $vehicle = new Vehicle([
                        'brandname' => $validateData['brandname'],
                        'user_id' => $user->id, // Use the authenticated user's ID
                        'color' => $validateData['color'],
                        'description' => $validateData['description'],
                        'model' => $validateData['model'],
                        'varient' => $validateData['varient'],
                        'chasie_number' => $validateData['chasie_number'],
                        'engine_number' => $validateData['engine_number'],
                        'purchase_price' => $validateData['purchase_price'],
                        'sale_price' => $validateData['sale_price'],
                        'image_path' => $imageName, // Save the image path
                        'buyer_id'=> $request->buyer_id,
                        'seller_id'=> $request->seller_id,
                        'brand_id'=> $brandid
                    ]);
                    $vehicle->save();
                    return response()->json([
                        'success' => true,
                        'message' => "Vehicle added successfully"
                    ], 200);
                } else {
                    return response()->json([
                        'success' => false,
                        'message' => "Image not provided"
                    ], 400);
                }
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
    
        public function singlevehicle (Request $request, $id){
        try{
            $user = $request->input('user');
            if ($user){
                $vehicle= DB::table('vehicle')->find($id);
                if (!$vehicle) {
                    return response()->json([
                        "success" => false,
                        'message' => 'Vehicle not found'
                    ], 404);
                }
                return response()->json([
                    'success'=>true,
                    'message'=>$vehicle
                ],200);
            }
            else{
                return response()->json([
                    "success" => false,
                    'message' => 'Please Login to access this information'
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
         public function allvehicle(){
        $vehicle = Vehicle::GetAllvehicle();
        return response()->json([
            "success" => true,
            'message' => $vehicle
        ], 200);
    }
        public function calculateProfit(Request $request)
    {    
    $vehicles = Vehicle::all(); 
    $profit = 0;
    foreach ($vehicles as $vehicle) {
        if ($vehicle->purchase_price !== null && $vehicle->sale_price !== null) {
            $profit += ($vehicle->sale_price - $vehicle->purchase_price);
        }
    }
    return response()->json([
        "success"=>true,
        'profit' => $profit,
    ], 200);
    }
    public function updateVehicle(Request $request, $id)
    {
        try {
            $user = $request->input('user');
            $vehicle = Vehicle::find($id); // Access the $user variable set by the middleware
    
            if ($vehicle && $user) {
                $validateData = $request->validate([
                    'brandname' => 'string|max:20',
                    'color' => 'string|max:10',
                    'description' => 'string|max:150',
                    'model' => 'numeric',
                    'varient' => 'string',
                    'chasie_number' => 'string',
                    'engine_number' => 'string',
                    'purchase_price' => 'numeric',
                    'sale_price' => 'numeric',
                    'image_path' => 'image',
                    'buyer_id' => 'numeric',  // Include this if you want to validate 'buyer_id'
                    'seller_id' => 'numeric',
                ]);
    
                // Use the update method to update the vehicle attributes
                $vehicle->update($validateData);
    
                // You can also set the buyer_id and seller_id like this if they are present in the request
               
    
                $vehicle->save();
    
                Log::info('Request information', ['request' => $user,$vehicle]);
    
                return response()->json([
                    'success' => true,
                    'message' => $request->all()
                ], 200);
            }
    
            else if(!$vehicle) {
                return response()->json([
                    'success' => false,
                    'message' => 'Vehicle not found'
                ], 401);
            }
        } catch (Exception $e) {
            // Handle exceptions, if any
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
    public function deleteVehicle(int $id)
    {
        $vehicle = Vehicle::find($id);
        if($vehicle){
        $vehicle->delete();
        return response()->json([
            "success" =>true,
            "message"=>"Deleted successfully"
        ],200);
        }
        else{
            return response()->json([
                "success" =>false,
                "message"=>"Vehicle not found"
            ],401);
        }
    }
}
