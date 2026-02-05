<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (!auth()->check()) {
            return redirect('/pages/login.html?redirect=' . urlencode($request->fullUrl()));
        }

        if (auth()->user()->role !== 'admin') {
            abort(403, 'YOU DO NOT HAVE AUTHORISATION TO ACCESS THIS.');
        }

        return $next($request);
    }
}
