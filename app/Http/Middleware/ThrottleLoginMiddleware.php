<?php

namespace App\Http\Middleware;

use App\Models\CompanySetting;
use App\Models\User;
use Closure;
use Illuminate\Cache\RateLimiter;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ThrottleLoginMiddleware
{
    protected $limiter;

    public function __construct(RateLimiter $limiter)
    {
        $this->limiter = $limiter;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {

        $user = User::Where('email', $request->email)->orWhere('staff_id', $request->email)->first();

        if ($user) {
            $companySetting = CompanySetting::firstWhere('company_id', $user->company_id);
            $maxAttempts = $companySetting?->no_of_login_attempt ?? 5; // Use default of 5 if not set
            $key = $request->ip() . ':' . $user->email; // Combine IP and user identifier
            $decaySeconds = 60 * 15; // 15 minutes

            if ($this->limiter->tooManyAttempts($key, $maxAttempts)) {
                return response()->json(['error' => "Too many login attempts. Please try again later, or contact your administrator if you forgot your password."], Response::HTTP_TOO_MANY_REQUESTS);
            }

            $this->limiter->hit($key, $decaySeconds);
        }

        return $next($request);
    }
}
