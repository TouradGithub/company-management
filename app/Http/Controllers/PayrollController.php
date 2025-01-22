<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Branch;
class PayrollController extends Controller
{
    public function create()
    {
        $branches = Branch::where('company_id',auth()->user()->model_id)->get(); // Assuming you have an Employee model
        return view('campany.payrolls.create', compact('branches'));
    }

}
