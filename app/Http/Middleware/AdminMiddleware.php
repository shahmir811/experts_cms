<?php

namespace App\Http\Middleware;

use Closure;
use Auth;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {

        if(Auth::check()) 
        {
            $user = Auth::user();
            if($user->hasRole(['super-admin', 'admin']))
            {
                return $next($request);
            }

            abort(403, "User is not authorized to access this page.");
        }

        abort(401, "User is not authorized to access this page.");
    }
}
