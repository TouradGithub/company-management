<?php

namespace App\Http\Controllers\Accounting;

use App\Http\Controllers\Controller;
use App\Models\Journal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class JournalController extends Controller
{


    public function get()
    {
        $journals = Journal::where('company_id' , getCompanyId())->get();
        return response()->json(['journals' => $journals]);
    }

    public function index()
    {

        return view('financialaccounting.journals.index');
    }

    public function store(Request $request)
    {
        $companyId = getCompanyId();

        $validated = $request->validate([
            'code' => "required|unique:journals,code,NULL,id,company_id,{$companyId}",
            'name' => "required|unique:journals,name,NULL,id,company_id,{$companyId}",
        ], [
            'code.required' => 'كود الدفتر مطلوب.',
            'code.unique' => 'هذا الكود مستخدم بالفعل ضمن الشركة.',
            'name.required' => 'اسم الدفتر مطلوب.',
            'name.unique' => 'هذا الاسم مستخدم بالفعل ضمن الشركة.',
        ]);

        Journal::create([
            'code' => $request->code,
            'name' => $request->name,
            'company_id' => $companyId,
        ]);
        return response()->json(['success' => true, 'status' => 'تم', 'message' => 'تم إضافة الدفتر بنجاح']);
    }

    public function edit($id)
    {
        $journal = Journal::find( $id);
        return response()->json(['success' => true, 'journal' => $journal]);
    }

    public function update(Request $request, $id)
    {
        $companyId = getCompanyId();

        $validated = $request->validate([
            'code' => "required|unique:journals,code,{$id},id,company_id,{$companyId}",
            'name' => "required|unique:journals,name,{$id},id,company_id,{$companyId}",
        ], [
            'code.required' => 'كود الدفتر مطلوب.',
            'code.unique' => 'هذا الكود مستخدم بالفعل ضمن الشركة.',
            'name.required' => 'اسم الدفتر مطلوب.',
            'name.unique' => 'هذا الاسم مستخدم بالفعل ضمن الشركة.',
        ]);

        $journal = Journal::find($id);
        $journal->update($validated);
        return response()->json(['success' => true, 'status' => 'تم', 'message' => 'تم تعديل الدفتر بنجاح']);
    }

    public function delete($id)
    {
        $journal = Journal::find( $id);
        $journal->delete();
        return response()->json(['success' => true, 'message' => 'تم حذف الدفتر بنجاح']);
    }
}
