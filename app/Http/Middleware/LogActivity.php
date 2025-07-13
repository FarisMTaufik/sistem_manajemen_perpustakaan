<?php

namespace App\Http\Middleware;

use App\Models\ActivityLog;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class LogActivity
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Proses request terlebih dahulu
        $response = $next($request);
        
        // Jangan log aktivitas untuk beberapa rute
        if ($this->shouldSkip($request)) {
            return $response;
        }
        
        // Log aktivitas setelah request diproses
        $this->logActivity($request);
        
        return $response;
    }
    
    /**
     * Memeriksa apakah request harus dilewati untuk logging.
     */
    protected function shouldSkip(Request $request): bool
    {
        // Lewati rute-rute yang tidak perlu di-log
        $skipRoutes = [
            '_debugbar',
            'livewire',
            'sanctum',
            'login',
            'logout',
            'assets',
            'favicon.ico',
            'robots.txt',
        ];
        
        foreach ($skipRoutes as $route) {
            if ($request->is($route) || str_contains($request->path(), $route)) {
                return true;
            }
        }
        
        // Lewati request AJAX dan API
        if ($request->ajax() || $request->expectsJson()) {
            return true;
        }
        
        return false;
    }
    
    /**
     * Log aktivitas pengguna.
     */
    protected function logActivity(Request $request): void
    {
        try {
            $action = $this->getAction($request);
            $module = $this->getModule($request);
            $description = $this->getDescription($request, $action, $module);
            
            $logData = [
                'user_id' => auth()->id(),
                'user_role' => auth()->user() ? auth()->user()->role : 'guest',
                'user_name' => auth()->user() ? auth()->user()->name : 'Guest',
                'action' => $action,
                'module' => $module,
                'description' => $description,
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'properties' => $this->getRequestProperties($request),
            ];
            
            ActivityLog::create($logData);
        } catch (\Exception $e) {
            // Log error silently, jangan sampai mengganggu alur aplikasi
            \Log::error('Error logging activity: ' . $e->getMessage());
        }
    }
    
    /**
     * Mendapatkan properties dari request yang akan disimpan.
     */
    protected function getRequestProperties(Request $request): ?array
    {
        // Jangan simpan data sensitif seperti password
        $properties = $request->except(['password', 'password_confirmation', '_token']);
        
        // Batasi ukuran data yang disimpan
        return !empty($properties) ? $properties : null;
    }
    
    /**
     * Mendapatkan jenis aksi dari request.
     */
    protected function getAction(Request $request): string
    {
        $method = $request->method();
        
        return match($method) {
            'GET' => 'view',
            'POST' => 'create',
            'PUT', 'PATCH' => 'update',
            'DELETE' => 'delete',
            default => 'other',
        };
    }
    
    /**
     * Mendapatkan modul dari request.
     */
    protected function getModule(Request $request): string
    {
        $path = $request->path();
        $segments = explode('/', $path);
        
        // Jika ada segment kedua, gunakan sebagai modul
        if (count($segments) >= 2) {
            return $segments[1];
        }
        
        return $segments[0] ?: 'home';
    }
    
    /**
     * Mendapatkan deskripsi dari request.
     */
    protected function getDescription(Request $request, string $action, string $module): string
    {
        $user = auth()->user();
        $path = $request->path();
        
        if (!$user) {
            return "Guest melakukan {$action} pada modul {$module} - {$path}";
        }
        
        return "{$user->name} ({$user->role}) melakukan {$action} pada modul {$module} - {$path}";
    }
}
