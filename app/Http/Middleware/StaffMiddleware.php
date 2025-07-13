<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class StaffMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Tambahkan debugging
        Log::info('StaffMiddleware dipanggil dengan URL: ' . $request->url());
        
        if (!Auth::check()) {
            Log::info('StaffMiddleware: User tidak login');
            return redirect()->route('login')->with('error', 'Anda harus login terlebih dahulu.');
        }
        
        Log::info('StaffMiddleware: User role: ' . Auth::user()->role);
        
        if (Auth::user()->role == 'staff') {
            return $next($request);
        }
        
        Log::info('StaffMiddleware: User tidak memiliki akses staff');
        return redirect()->route('login')->with('error', 'Anda tidak memiliki akses untuk halaman ini.');
    }
}
