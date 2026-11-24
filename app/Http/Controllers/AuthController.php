<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    // Страница регистрации
    public function showRegister()
    {
        return view('register');
    }

    // Регистрация пользователя
    public function storeRegister(Request $request)
    {
        $password = Hash::make($request->password);

        User::create([
            'login' => $request->login,
            'email' => $request->email,
            'password' => $password,
        ]);

        return redirect()
            ->route('show.login')
            ->with('success', 'Успешная регистрация');
    }

    // Страница авторизации
    public function showLogin()
    {
        return view('login');
    }

    // Авторизация пользователя
    public function storeLogin(Request $request)
    {
        $user = User::where('email', $request->email)->first();

        if ($user && Hash::check($request->password, $user->password)) {

            Auth::login($user);

            $request->session()->regenerate();

            if ($user->role === 'admin') {
                return redirect()
                    ->route('show.admin')
                    ->with('success', 'Добро пожаловать в кабинет администратора!');
            }

            return redirect()
                ->intended(route('show.profile'))
                ->with('success', 'Добро пожаловать в кабинет пользователя!');
        }

        return redirect()
            ->back()
            ->with('error', 'Неверный логин или пароль');
    }

    // Выход из аккаунта
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()
            ->route('show.login')
            ->with('success', 'Вы вышли из учетной записи');
    }
}
