<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckTokenMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();

        // Check if user exists and the name is not 'superadmin'
        if ($user && $user->name !== 'superadmin') {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        // If user is 'superadmin' or no user is authenticated, continue with the request
        return $next($request);
    }
}
