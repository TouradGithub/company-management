<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Branch;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
class BranchController extends Controller
{
    public function create()
    {
        return view('branches.create'); // Pointing to resources/views/company/create.blade.php
    }


    public function store(Request $request)
    {
        // Validate incoming data
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'name_admin_company' => 'required|string|max:255',
            'email' => 'required|email|unique:companies,email',
            'password' => 'required|string|min:8',
        ]);
        // dd(auth()->user());

        // Create a new company
        $branch =  Branch::create([
            'name' => $validated['name'],
            'name_admin_company' => $validated['name_admin_company'],
            'email' => $validated['email'],
            'company_id'=>auth()->user()->model_id,
            'password' => Hash::make($validated['password']),
        ]);

        User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'model_type' =>"BRANCH",
            'model_id' =>$branch->id,
            'is_admin' =>1,
        ]);


        return redirect()->back()->with('success', 'Branch added successfully!');
    }

    public function index()
    {
        $companies = Branch::all();
        return view('branches.index', compact('companies')); // Adjust view path as needed
    }

    /**
     * Show a form to edit a company.
     */
    public function edit($id)
    {
        $company = Branch::findOrFail($id);
        return view('branches.edit', compact('company')); // Adjust view path as needed
    }

    /**
     * Update an existing company.
     */
    public function update(Request $request, $id)
    {
        // Validate the data
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'name_admin_company' => 'required|string|max:255',
            'email' => 'required|email|unique:companies,email,' . $id,
            'password' => 'nullable|string|min:8',
        ]);
        // return $request->password;re
// return auth()->user()->model_id;
        // Find the company and update its data
        $branch = Branch::findOrFail($id);
        $branch->name = $validated['name'];
        $branch->company_id =auth()->user()->model_id;
        $branch->name_admin_company = $validated['name_admin_company'];
        $branch->email = $validated['email'];
        if (!empty($validated['password'])) {
            $branch->password = bcrypt($validated['password']);
        }
        $branch->save();

        $user =  User::where([
            'model_type' =>"BRANCH",
            'model_id' =>$branch->id,
            'is_admin' =>1,
        ])->first();

        dd( $user);

        // $user->email =$validated['email'];
        // $user->password =Hash::make($request->password);
        // $user->save();

        return redirect()->route('branches.index')->with('success', 'Branch updated successfully!');
    }

    /**
     * Delete a company.
     */
    public function destroy($id)
    {
        $branch = Branch::findOrFail($id);
        // $user =  User::where([
        //     'model_type' =>"BRANCH",
        //     'model_id' =>$branch->id,
        //     'is_admin' =>1,
        // ])->first()->delete();
        $branch->delete();


        return redirect()->route('branches.index')->with('success', 'Branch deleted successfully!');
    }
}
