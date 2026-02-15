<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class BlockAdminFromUserRoutes
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // If user is admin, redirect to admin dashboard
        if (auth()->check() && auth()->user()->is_admin) {
            return redirect()->route('admin.dashboard')
                ->with('error', 'Admin tidak dapat mengakses halaman user. Silakan gunakan dashboard admin.');
        }

        return $next($request);
    }
}
