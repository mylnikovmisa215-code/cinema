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
             if ($user->role === 'admin'){
                return redirect()->route('show.admin')->with('success', 'Добро пожаловать в кабинет администратора!');
            }else{
                return redirect()->route('show.profile')->with('success', 'Добро пожаловать в кабинет пользователя!');
            }


        }return redirect()->back()->with('success', 'Неверный логин или пароль');
        
    }

    public function logout(){
        
        Auth::logout();
        return redirect()->route('show.login')->with('success', 'Вы вышли из учетной записи');

    }

}

