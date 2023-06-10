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
        if(!Session::has('company_id')){
            if(!$request->header('company-slug')){
                return response()->json(['error' => 'Company slug is required', 'data' => null], 422);
            }else{
                $slug = $request->header('company-slug');
                $company = Company::whereSlug($slug)->first();
                if(!$company){
                    return response()->json(['error' => 'Company with slug: '.$slug.' not found', 'data' => null], 422);
                }else{
                    Session::put('company_id', $company->id);
                    Session::put('company_name', $company->name);
                    Session::put('company_url', $company->url);
                    Session::put('company_location', $company->location);   
                }
            }
        }
        
        return $next($request);
    }
}
