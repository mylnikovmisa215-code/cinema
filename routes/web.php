<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AdminController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Регистрация
Route::get('/register', [AuthController::class, 'showRegister'])->name('show.register');
Route::post('/register', [AuthController::class, 'storeRegister'])->name('store.register');

// Авторизация
Route::get('/login', [AuthController::class, 'showLogin'])->name('show.login');
Route::post('/login', [AuthController::class, 'storeLogin'])->name('store.login');

// Личный кабинет
Route::get('/profile', [UserController::class, 'ShowProfile'])->name('show.profile');

// Выход
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Маршруты администратора
Route::middleware('check.admin')->group(function () {

    Route::get('/admin', [AdminController::class, 'showAdmin'])
        ->name('show.admin');

    Route::prefix('admin')->name('admin.')->group(function () {

        // Залы
        Route::get('/halls', [AdminController::class, 'halls'])->name('halls');
        Route::get('/halls/create', [AdminController::class, 'createHall'])->name('halls.create');
        Route::post('/halls', [AdminController::class, 'storeHall'])->name('halls.store');

        // Жанры
        Route::get('/genres', [AdminController::class, 'genres'])->name('genres');
        Route::get('/genres/create', [AdminController::class, 'createGenre'])->name('genres.create');
        Route::post('/genres', [AdminController::class, 'storeGenre'])->name('genres.store');

        // Фильмы
        Route::get('/movies', [AdminController::class, 'movies'])->name('movies');
        Route::get('/movies/create', [AdminController::class, 'createMovie'])->name('movies.create');
        Route::post('/movies', [AdminController::class, 'storeMovie'])->name('movies.store');

        // Сеансы
        Route::get('/screenings', [AdminController::class, 'screenings'])
            ->name('screenings');

        Route::get('/screenings/create', [AdminController::class, 'createScreening'])
            ->name('screenings.create');

        Route::post('/screenings', [AdminController::class, 'storeScreening'])
            ->name('screenings.store');

        // Удаление
        Route::delete('/halls/{id}', [AdminController::class, 'destroyHall'])
            ->name('halls.destroy');

        Route::delete('/movies/{id}', [AdminController::class, 'destroyMovie'])
            ->name('movies.destroy');

        Route::delete('/screenings/{id}', [AdminController::class, 'destroyScreening'])
            ->name('screenings.destroy');
    });
});