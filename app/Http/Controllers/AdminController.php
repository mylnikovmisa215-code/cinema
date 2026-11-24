<?php

namespace App\Http\Controllers;

use App\Models\Hall;
use App\Models\Genre;
use App\Models\Movie;
use App\Models\Screening;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class AdminController extends Controller
{
    // Главная страница администратора
    public function showAdmin()
    {
        return view('admin');
    }

    // =====================================
    // ЗАЛЫ
    // =====================================

    public function halls()
    {
        $halls = Hall::all();

        return view('admin', compact('halls'));
    }

    public function createHall()
    {
        return view('admin');
    }

    public function storeHall(Request $request)
    {
        $validated = $request->validate([
            'name_hall' => 'required|string|max:255',
            'rows_count' => 'required|integer|min:1',
            'seats_count' => 'required|integer|min:1',
        ]);

        Hall::create($validated);

        return redirect()
            ->route('admin.halls')
            ->with('success', 'Зал успешно добавлен!');
    }

    // =====================================
    // ЖАНРЫ
    // =====================================

    public function genres()
    {
        $genres = Genre::all();

        return view('admin', compact('genres'));
    }

    public function createGenre()
    {
        return view('admin');
    }

    public function storeGenre(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:genres,name',
        ]);

        Genre::create($validated);

        return redirect()
            ->route('admin.genres')
            ->with('success', 'Жанр успешно добавлен!');
    }

    // =====================================
    // ФИЛЬМЫ
    // =====================================

    public function movies()
    {
        $movies = Movie::with('genres')->get();

        return view('admin', compact('movies'));
    }

    public function screenings()
    {
        $screenings = Screening::with(['movie', 'hall'])
            ->orderBy('start_time')
            ->get();

        return view('admin', compact('screenings'));
    }

    public function createMovie()
    {
        $genres = Genre::all();
        $halls = Hall::all();

        return view('admin', compact('genres', 'halls'));
    }

    // Создание зала, жанра, фильма и первого сеанса
    public function storeMovie(Request $request)
    {
        $validated = $request->validate([

            // Данные зала
            'name_hall' => 'required|string|max:255',
            'total_seats' => 'required|integer|min:1',
            'seats_per_row' => 'required|integer|min:1',

            // Данные жанра
            'genre_name' => 'required|string|max:255',

            // Данные фильма
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'duration' => 'required|integer|min:1',
            'year' => 'required|integer|min:1888|max:2100',
            'age_rating' => 'required|in:0+,6+,12+,16+,18+',
            'trailer_url' => 'nullable|url',

            // Данные первого сеанса
            'start_time' => 'required|date|after:now',
            'price' => 'required|numeric|min:0',
            'format' => 'required|in:2D,3D,IMAX,VIP',
        ]);

        // Проверка количества мест
        if ($validated['total_seats'] % $validated['seats_per_row'] !== 0) {
            return redirect()
                ->back()
                ->withErrors([
                    'total_seats' => 'Общее количество мест должно делиться на количество мест в ряду без остатка.'
                ])
                ->withInput();
        }

        DB::transaction(function () use ($validated) {

            // Создание зала
            $hall = Hall::create([
                'name_hall' => $validated['name_hall'],
                'rows_count' => intdiv(
                    $validated['total_seats'],
                    $validated['seats_per_row']
                ),
                'seats_count' => $validated['seats_per_row'],
            ]);

            // Создание или поиск жанра
            $genre = Genre::firstOrCreate([
                'name' => $validated['genre_name'],
            ]);

            // Создание фильма
            $movie = Movie::create([
                'title' => $validated['title'],
                'description' => $validated['description'],
                'duration' => $validated['duration'],
                'year' => $validated['year'],
                'age_rating' => $validated['age_rating'],
                'trailer_url' => $validated['trailer_url'] ?? null,
            ]);

            // Привязка жанра к фильму
            $movie->genres()->attach($genre->id);

            // Создание первого сеанса
            Screening::create([
                'movie_id' => $movie->id,
                'hall_id' => $hall->id,
                'start_time' => $validated['start_time'],
                'price' => $validated['price'],
                'format' => $validated['format'],
            ]);
        });

        return redirect()
            ->route('admin.movies')
            ->with(
                'success',
                'Зал, жанр, фильм и первый сеанс успешно добавлены!'
            );
    }

    // Форма добавления дополнительного сеанса
    public function createScreening()
    {
        $movies = Movie::all();
        $halls = Hall::all();

        return view('admin', compact('movies', 'halls'));
    }

    // Сохранение дополнительного сеанса
    public function storeScreening(Request $request)
    {
        $validated = $request->validate([
            'movie_id' => 'required|exists:movies,id',
            'hall_id' => 'required|exists:halls,id',
            'start_time' => 'required|date|after:now',
            'price' => 'required|numeric|min:0',
            'format' => 'required|in:2D,3D,IMAX,VIP',
        ]);

        if ($this->hasScreeningConflict(
            $validated['hall_id'],
            $validated['movie_id'],
            $validated['start_time']
        )) {
        return redirect()
            ->back()
            ->withErrors([
                'start_time' => 'В выбранном зале уже есть сеанс, пересекающийся по времени с этим показом.'
            ])
            ->withInput();
}

        Screening::create($validated);

        return redirect()
            ->route('admin.screenings')
            ->with('success', 'Дополнительный сеанс успешно добавлен!');
    }
    // Проверка пересечения сеансов
    private function hasScreeningConflict($hallId, $movieId, $startTime)
    {
        $newStart = Carbon::parse($startTime);

        $movie = Movie::findOrFail($movieId);

        $newEnd = $newStart->copy()->addMinutes($movie->duration);

        $screenings = Screening::with('movie')
            ->where('hall_id', $hallId)
            ->get();

        foreach ($screenings as $screening) {

            $existingStart = Carbon::parse($screening->start_time);

            $existingEnd = $existingStart->copy()
                ->addMinutes($screening->movie->duration);

            if (
                $newStart->lt($existingEnd) &&
                $newEnd->gt($existingStart)
            ) {
                return true;
            }
        }

        return false;
    }

   

    public function destroyHall($id)
    {
        $hall = Hall::findOrFail($id);

        if ($hall->screenings()->exists()) {
            return redirect()
                ->back()
                ->with('error', 'Нельзя удалить зал, в котором есть сеансы.');
        }

        $hall->delete();

        return redirect()
            ->back()
            ->with('success', 'Зал успешно удалён!');
    }

    public function destroyMovie($id)
    {
        $movie = Movie::findOrFail($id);

        if ($movie->screenings()->exists()) {
            return redirect()
                ->back()
                ->with('error', 'Нельзя удалить фильм, у которого есть сеансы.');
        }

        $movie->genres()->detach();
        $movie->delete();

        return redirect()
            ->back()
            ->with('success', 'Фильм успешно удалён!');
    }

    public function destroyScreening($id)
    {
        $screening = Screening::findOrFail($id);

        if ($screening->tickets()->exists()) {
            return redirect()
                ->back()
                ->with('error', 'Нельзя удалить сеанс, на который уже куплены билеты.');
        }

        $screening->delete();

        return redirect()
            ->back()
            ->with('success', 'Сеанс успешно удалён!');
    }
}