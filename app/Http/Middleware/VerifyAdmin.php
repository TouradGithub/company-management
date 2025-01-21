<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

use Illuminate\Support\Facades\Auth;
class VerifyAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check() && Auth::user()->model_type != 'USERADMIN') {
            Auth::logout();

            return redirect()->route('login')->with('error', 'Unauthorized access!');
        }

        return $next($request);
        // Redirect or abort if the user is not an admin
    }
}
