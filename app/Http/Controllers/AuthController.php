<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showRegister(){
        return view('register');
    }
    public function storeRegister(Request $request){

        $password = Hash::make($request->password);

        User::create([
            'login'=>$request->login,
            'email'=>$request->email,
            'password'=>$password,
        ]);
        return redirect()->route('show.login')->with('success', 'Успешная регистрация');
    }

    public function showLogin(){
        return view('login');
    }

    public function storeLogin(Request $request){
        $user=User::where('email', $request->email)->first();

        if($user&&Hash::check($request->password, $user->password)){

            Auth::login($user);
            return redirect()->route('show.profile')->with('succes', 'Добро пожаловать в личный кабинет');

        }return redirect()->back()->with('success', 'Неверный логин или пароль');
        
    }

    public function logout(){
        
        Auth::logout();
        return redirect()->route('show.login')->with('success', 'Вы вышли из учетной записи');

    }

}

