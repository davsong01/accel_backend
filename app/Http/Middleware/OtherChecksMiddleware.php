<?php

namespace App\Http\Middleware;

use App\Models\RolePermission;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Response;

class OtherChecksMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if user is active
        if (auth()->user()) {
            if (auth()->user()->status == 'inactive') {
                return response()->json(['error' => 'Your account is not active!'], 403);
            }
        }
        // Check if user has access to resource
        $name = Route::currentRouteName();
        
        if (isset($name)) {
            $permCheck = permissionsCheck($name);
            
            if (isset($permCheck)) {
                if (isset($permCheck['permission']) && $permCheck['permission'] == true) {
                    return $next($request);
                } else {
                    return response()->json(['auth-error' => $permCheck['error'] ?? '', 'data' => []], $permCheck['code'] ?? 403);
                }
            } else {
                return response()->json(['auth-error' => 'Something went wrong while trying to validate access to resource!', 'data' => []], 403);
            }
        } else {
            return $next($request);
        }

        return $next($request);
    }
}
