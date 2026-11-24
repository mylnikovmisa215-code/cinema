<?php

namespace App\Http\Controllers;

use App\Models\Movie;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    // Главная страница с каталогом фильмов и расписанием сеансов
    public function showCatalog()
    {
        $movies = Movie::with([
            'genres',
            'images',
            'screenings' => function ($query) {
                $query->where('start_time', '>=', now())
                    ->orderBy('start_time');
            },
            'screenings.hall',
        ])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('welcome', compact('movies'));
    }

    // Личный кабинет с забронированными билетами
    public function showProfile()
    {
        $tickets = Auth::user()
            ->tickets()
            ->with([
                'screening.movie',
                'screening.hall',
            ])
            ->latest()
            ->get();

        return view('profile', compact('tickets'));
    }
}
