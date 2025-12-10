<?php

use App\Http\Controllers\ConnexionController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/test', function () {
    return ['message' => 'API OK'];
});

Route::prefix('auth')->group(function () {
    Route::get('/user',[ConnexionController::class,'index']);
    Route::post('/login', [ConnexionController::class, 'login']);
    Route::post('/logout', [ConnexionController::class, 'logout'])->middleware('auth');
    Route::get('/check', [ConnexionController::class, 'checkAuth']);
    Route::post('/forgot-password', [ConnexionController::class, 'forgotPassword']);
    // Route::post('/reset-password', [ConnexionController::class, 'resetPassword']);
});
