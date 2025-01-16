<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Company;
class CompanyController extends Controller
{
    public function create()
    {
        return view('campany.create'); // Pointing to resources/views/company/create.blade.php
    }

    public function index()
    {
        $companies = Company::all();
        return view('campany.index', compact('companies')); // Pointing to resources/views/company/create.blade.php
    }

    /**
     * Store the company data in the database.
     */
    public function store(Request $request)
    {
        // Validate the incoming data
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:companies,email',
            'password' => 'required|string|max:500',
            'start_date' => 'required|string|max:500',
            'end_date' => 'required|string|max:500',
            // 'status' => 'required|string|max:500',
        ]);

        // Create a new company record
        $company = Company::create($validated);

        // Redirect with a success message
        return redirect()->route('company.create')->with('success', 'Company created successfully!');
    }
}
