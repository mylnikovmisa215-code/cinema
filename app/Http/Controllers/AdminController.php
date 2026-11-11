<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function showAdmin(){
        return view('admin');
    }

    public function halls(){
        $halls = Hall::all();
        return view('admin', compact('halls'));
    }

    public function storeHall(Request $request){
        Hall::create($request->all());
        return redirect()->route('admin.halls')->with('succes', 'Зал добавлен');
    }

    public function createHall(){
        return view('admin');
    }

    public function genres(){
        $genres = Genre::all();
        return view('admin', compact('genres'));
    }

    public function createGenre(){
        return view('admin')
    }

    public function storeGenre(Request $request){
        Genre::create([
            'name'=>$request->name,
        ]);
        return redirect()->route('admin.genres')->width('succes', 'Жанр добавлен');
    }

    public function movies(){
        $movies = Movie::all();
        return view('admin', compact('movies'));
    }

    public function createMovie(){
        $movies = Movie::all();
        return view('admin', compact('movies'));
    }

    public function storeMovie(Request $request){
        $movie = Movie::create([
            'title'=>$request->title,
            'description'=>$request->description,
            'duration'=>$request->duration,
            'year'=>$request->year,
            'age_rating'=>$request->age_rating,
            'trailer_url'=>$request->trailer_url,
        ]);
        $movie->genres()->attach($request->genres);

        return redirect()->route('admin.movies')->width('succes', 'Фильм добавлен');
    }
}
