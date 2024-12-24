<?php

use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:api');

Route::group(['namespace' => 'App\Http\Controllers'], function() {
    Route::controller(AuthController::class)->group(function () {
       Route::post('register', 'register')->name('register');
       Route::post('login', 'login')->name('login');
    });
});