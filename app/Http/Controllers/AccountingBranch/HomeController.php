<?php

namespace App\Http\Controllers\AccountingBranch;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        return view('financialaccountingbranch.index'); // Pointing to resources/views/company/create.blade.php
    }
}
