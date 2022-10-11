<?php

use App\Http\Controllers\AppController;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

Route::get('/', [AppController::class, 'home']);
Route::post('/', [AppController::class, 'edit']);
Route::get('/login', [AuthController::class, 'login']);
Route::post('/login', [AuthController::class, 'loginPost']);
