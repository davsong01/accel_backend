<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Response;

class CompanyCheckMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Get company
        if (!Session::has('company_id')) {
            if (Session::has('company_slug')) {
                $slug = Session::get('company_slug');
            } else {
                if (auth()->user()) {
                    $company = Company::whereId(auth()->user()->company_id)->first();
                }
            }

            if (!$company) {
                return response()->json(['error' => 'Company with slug: ' . $slug . ' not found', 'data' => null], 412);
            } else {
                Session::put('company_id', $company->id);
                Session::put('company_name', $company->name);
                Session::put('company_url', $company->url);
                Session::put('company_location', $company->location);
                Session::put('company_slug', $company->slug);
            }
        }

        return $next($request);
    }
}
