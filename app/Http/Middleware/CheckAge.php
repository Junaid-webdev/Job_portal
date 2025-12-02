<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckAge
{
    public function handle(Request $request, Closure $next)
    {
        if ($request->input('age') < 18) {
            return redirect('/')->with('error', 'You must be 18+');
        }

        return $next($request);
    }
}
