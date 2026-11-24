<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check()) {
            return redirect()
                ->route('show.login')
                ->with('error', 'Необходимо авторизоваться.');
        }

        if (Auth::user()->role !== 'admin') {
            abort(403, 'У вас нет прав для просмотра этой страницы.');
        }

        return $next($request);
    }
}