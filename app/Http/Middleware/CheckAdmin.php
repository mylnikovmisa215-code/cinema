<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if(!Auth()->check()){
            return redirect()->route('show.login')->with('error', 'необходимо авторизоваться');
        }

        if(Auth::user()-> role!=='admin'){
            abort(403, 'у вас нет прав на просмотр этой страницы');
        }

        return $next($request);
    }
}
