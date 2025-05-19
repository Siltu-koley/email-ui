<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DkimController;
use App\Http\Controllers\EmailController;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

Route::get('/zone-auth', [AuthController::class, 'zoneAuth']);
Route::post('/smtp-auth', [AuthController::class, 'smtpAuth']);
Route::post('/get-dkim-config', [DkimController::class, 'getDkim']);
Route::get('/get-ip/{email}', [EmailController::class, 'getIp']);
Route::get('/checkmongo', [AuthController::class, 'checkmongo']);