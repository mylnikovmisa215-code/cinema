<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/register', [AuthController::class, 'showRegister'])->name('show.register');
Route::post('/register', [AuthController::class, 'storeRegister'])->name('store.register');

Route::get('/login', [AuthController::class, 'showLogin'])->name('show.login');
Route::post('/login', [AuthController::class, 'storeLogin'])->name('store.login');


Route::get('/profile', [UserController::class, 'ShowProfile'])->name('show.profile');


Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
