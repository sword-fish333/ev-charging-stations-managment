<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\CompanyController;
use App\Http\Controllers\Api\StationController;
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
Route::group(['prefix' => '/company'], function () { //companies CRUD
    Route::get('/',[CompanyController::class,'index']);
    Route::get('/{company_id}/children',[CompanyController::class,'childCompanies']);
    Route::post('/',[CompanyController::class,'store']);
    Route::post('/{company}',[CompanyController::class,'update']);
    Route::delete('/{company}',[CompanyController::class,'delete']);
    Route::get('/{company}/charging-stations',[CompanyController::class,'chargingStations']); //all charging stations ordered by increasing distance from a company

});

Route::group(['prefix' => '/station'], function () {  //stations CRUD
    Route::get('/',[StationController::class,'index']);
    Route::post('/',[StationController::class,'store']);
    Route::post('/{station}',[StationController::class,'update']);
    Route::delete('/{station}',[StationController::class,'delete']);
});
