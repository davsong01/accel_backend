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
        // Check if user has access to page
        $name = Route::currentRouteName();

        $user_role_ids = array_unique(array_merge(Session::get('individual_permissions'), Session::get('staff_roles')));
        if (isset($name)) {
            // check if route is permissioned
            $permission = RolePermission::select('role_ids', 'status', 'id', 'type')->where(['route' => $name, 'type' => auth()->user()->type])->first();
            if (!$permission) return $next($request);

            // Check status of permission
            if ($permission->status == 'inactive') return response()->json(['error' => 'This menu is not active!', 'data' => null], 403);

            // confirm permission has roles
            if (is_null($permission->role_ids)) return $next($request);
            
            $allowed_roles = explode(",", $permission->role_ids);
            $check = !empty(array_intersect($allowed_roles, $user_role_ids));
            if (!$check) return response()->json(['auth-error' => 'You are not permitted to use this resource!', 'data' => null], 403);
        } else {
            return $next($request);
        }

        return $next($request);
    }
}