<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PageController;
use App\Http\Controllers\VehicleController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\brandController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});




// Customer ROUTES
Route::group(['middleware' => ['customAuth']], function () {
    Route::post('/createcustomer', [CustomerController::class, 'createcustomer']);
});


// VEHICLE ROUTES
Route::group(['middleware' => ['customAuth']], function () {
    Route::get('/singlevehicle/{id}', [VehicleController::class, 'singlevehicle']);
    Route::post('/postvehicle', [VehicleController::class, 'postvehicle']);
    Route::post('/addbrand', [brandController::class, 'addbrand']);
    Route::get('/getbrand', [brandController::class, 'allbrand']);
    Route::get('/allvehicle', [VehicleController::class, 'allvehicle']);
    Route::get('/getprofit', [VehicleController::class, 'calculateProfit']);
    Route::post('/updatevehicle/{id}', [VehicleController::class, 'updateVehicle']);
    Route::delete('/deletevehicle/{id}', [VehicleController::class, 'deleteVehicle']);
});


// USER ROUTES
Route::post('/login',[PageController::class,'login']);
Route::post('/users',[PageController::class,'store']);

Route::group(['middleware' => ['customAuth']], function () {
    Route::get('/showusers',[PageController::class,'show']);
    Route::put('/updateusers/{id}',[PageController::class,'update']);
    Route::get('/me',[PageController::class,'singleuser']);
});



