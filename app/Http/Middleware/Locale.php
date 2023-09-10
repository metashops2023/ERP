<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class Locale
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $availableLang = ['en', 'ar'];
        $lang = session('lang');
        $prepareLang = in_array($lang, $availableLang) ? $lang : config('app.locale');
        app()->setlocale($prepareLang);
        return $next($request);
    }
}
