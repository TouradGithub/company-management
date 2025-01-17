<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class EmployeeController extends Controller
{

    public function create()
    {
        return view('employees.create');
    }

    public function store(Request $request)
    {
        // التحقق من البيانات
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'job' => 'required|string|max:255',
            'basic_salary' => 'required|numeric|min:0',
            'housing_allowance' => 'required|numeric|min:0',
            'food_allowance' => 'required|numeric|min:0',
            'transportation_allowance' => 'required|numeric|min:0',
        ]);

        // إنشاء موظف جديد
        Employee::create($validated);

        // إعادة التوجيه مع رسالة نجاح
        return redirect()->route('employees.create')->with('success', 'تم إنشاء الموظف بنجاح!');
    }
}
