<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class SetLocale
{
    public function handle(Request $request, Closure $next)
    {
        // Set the locale based on the request or default to 'en'
        $locale = $request->header('Accept-Language', 'en');

        // Use app()->setLocale instead of setlocale
        app()->setLocale($locale);

        return $next($request);
    }
}
