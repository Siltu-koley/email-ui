<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DkimController;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

Route::get('/zone-auth', [AuthController::class, 'zoneAuth']);
Route::post('/get-dkim-config', [DkimController::class, 'getDkim']);