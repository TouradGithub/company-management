<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class VerifyCompanyInformation
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {

        $company = getCompany();
        $missingField = collect([
            'name' => $company->name,
            'phone_number' => $company->phone_number,
            'start_date' => $company->start_date,
            'end_date' => $company->end_date,
            'tax_number' => $company->tax_number,
            'address' => $company->address,
        ])->contains(function ($value) {
            return empty($value);
        });

        if ($missingField) {
            return redirect()->route('update.company.info.index')
                ->with('error', 'يرجى إكمال بيانات الشركة قبل المتابعة.');
        }

        return $next($request);
    }
}
