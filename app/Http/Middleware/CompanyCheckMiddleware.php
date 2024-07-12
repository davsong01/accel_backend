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

                    $company = Company::with(['settings' => function ($query) {
                        $query->select('id', 'company_id', 'company_logo', 'company_secondary_logo', 'company_name', 'address', 'about', 'currency');
                        $query->with(['currencyData' => function ($query) {
                            $query->select('id', 'symbol', 'identifier', 'name','code'); 
                        }]);
                    }])->whereId(auth()->user()->company_id)->first();
                    // $company = Company::with('settings:id,company_id,company_logo,company_secondary_logo,company_name,address,about,currency')->whereId(auth()->user()->company_id)->first();
                }
            }
            $company->settings->raw_logo = $company->settings->getRawOriginal('company_logo');
            
            if (!$company) {
                return response()->json(['error' => 'Company with slug: ' . $slug . ' not found', 'data' => null], 412);
            } else {
                Session::put('company_id', $company->id);
                Session::put('company_package_ids', $company->package_ids);
                Session::put('company_name', $company->name);
                Session::put('company_url', $company->url);
                Session::put('company_location', $company->location);
                Session::put('company_slug', $company->slug);
                Session::put('settings', $company->settings->toArray());
            }
        }
        return $next($request);
    }
}
