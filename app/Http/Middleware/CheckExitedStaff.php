<?php

namespace App\Http\Middleware;

use App\Models\EmployeeExit;
use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckExitedStaff
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
            $record = EmployeeExit::firstWhere(['staff_id' => auth()->user()->staff?->id, 'status' => 'APPROVED']);
        if (auth()->check() && $record) {
                auth()->user()->currentAccessToken()->delete();
                cookie()->forget('token');
                session()->flush();
            return  response()->json([
                'error' => 'You are no longer an active staff member.'
            ], 412);
        }

        return $next($request);
    }
}
