<?php

namespace App\Http\Controllers\Accounting;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        return view('financialaccounting.index'); // Pointing to resources/views/company/create.blade.php
    }
}
