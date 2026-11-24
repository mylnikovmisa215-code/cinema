<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\TicketController;
use Illuminate\Support\Facades\Route;

Route::get('/', [UserController::class, 'showCatalog'])
    ->name('catalog');

// Регистрация
Route::get('/register', [AuthController::class, 'showRegister'])->name('show.register');
Route::post('/register', [AuthController::class, 'storeRegister'])->name('store.register');

// Авторизация
Route::get('/login', [AuthController::class, 'showLogin'])->name('show.login');
Route::post('/login', [AuthController::class, 'storeLogin'])->name('store.login');

// Личный кабинет
Route::get('/profile', [UserController::class, 'ShowProfile'])->name('show.profile');

// Выбор мест для сеанса
Route::middleware('auth')->group(function () {
    Route::get('/screenings/{id}/seats', [TicketController::class, 'showSeats'])->name('tickets.seats');
    Route::post('/screenings/{id}/seats', [TicketController::class, 'store'])->name('tickets.store');
    Route::delete('/tickets/{id}', [TicketController::class, 'destroy'])->name('tickets.destroy');
    Route::post('/tickets/{id}/pay', [TicketController::class, 'pay'])->name('tickets.pay');
});

// Выход
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Маршруты администратора
Route::middleware('check.admin')->group(function () {

    Route::get('/admin', [AdminController::class, 'showAdmin'])->name('show.admin');

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

        // Билеты
        Route::get('/tickets', [AdminController::class, 'tickets'])->name('tickets');

        // Сеансы
        Route::get('/screenings', [AdminController::class, 'screenings'])->name('screenings');

        Route::get('/screenings/create', [AdminController::class, 'createScreening'])->name('screenings.create');

        Route::post('/screenings', [AdminController::class, 'storeScreening'])->name('screenings.store');

        // Удаление
        Route::delete('/halls/{id}', [AdminController::class, 'destroyHall'])->name('halls.destroy');

        Route::delete('/movies/{id}', [AdminController::class, 'destroyMovie'])->name('movies.destroy');

        Route::delete('/screenings/{id}', [AdminController::class, 'destroyScreening'])->name('screenings.destroy');
    });
});