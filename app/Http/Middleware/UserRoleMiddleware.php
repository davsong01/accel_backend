<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\Role;
use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Response;

class UserRoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if user is active
        if (auth()->user()->status != 'active') {
            return response()->json(['error' => 'Account is inactive!', 'data' => null], 403);
        }

        if (!Session::has('user_roles')) {
            // Get user type
            if (auth()->user()->type == 'customer') {
                // Get roles from customer
                $user_roles = auth()->user()->customer->roles->pluck('id')->toArray();
                if (!$user_roles) {
                    return response()->json(['error' => 'No roles found for this user', 'data' => null], 412);
                } else {
                    Session::put('customer_id',  auth()->user()->customer->id);
                    Session::put('customer_roles',  $user_roles);
                }
            }
            if (auth()->user()->type == 'staff') {
                // Get roles from Staff
                $user_roles = auth()->user()->staff->roles->pluck('id')->toArray();
               
                $user_roles = array_merge($user_roles, [(int) env('DEFAULT_ROLE_ID')]); // default role
                if (!$user_roles) {
                    return response()->json(['error' => 'No roles found for this user', 'data' => null], 412);
                } else {
                    // $defaultPermissions = Role::where('id', env('DEFAULT_ROLE_ID'));
                    //inject default role here and merge
                    $individual_permissions = auth()->user()->user_permissions;
                    // $individual_permissions = array_merge($individual_permissions, $defaultPermissions);
                    
                    if ($individual_permissions) {
                        $individual_permissions = explode(',', $individual_permissions);
                        Session::put('individual_permissions', $individual_permissions);
                    }
                    
                    Session::put('staff_id',  auth()->user()->staff->id);
                    Session::put('staff_roles',  $user_roles);
                }
            }
        }

        return $next($request);
    }
}
