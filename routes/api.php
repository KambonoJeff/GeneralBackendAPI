<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TaskController;
/*
--------------------------------------------------------------------------
| API Routes
--------------------------------------------------------------------------
*/
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/Login',[AuthController::class,'login']);
Route::post('/Register',[AuthController::class,'register']);
Route::resource('/tasks', TaskController::class);
Route::group(['middleware' =>['auth:sanctum']], function(){
    Route::post('/Logout',[AuthController::class,'logout']);

});
