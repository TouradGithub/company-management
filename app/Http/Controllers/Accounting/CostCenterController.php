<?php

namespace App\Http\Controllers\Accounting;

use App\Http\Controllers\Controller;
use App\Models\Account;
use App\Models\AccountType;
use App\Models\CostCenter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CostCenterController extends Controller
{
    public function index(){

        $costcenters = CostCenter::where('company_id', getCompanyId())->get();

        return view('financialaccounting.costCenter.index', compact('costcenters'));
    }

    public function store(Request $request)
    {
        // التحقق من صحة البيانات
        $validated = $request->validate([
            'code' => 'required',
            'name' => 'required',
        ], [
            'account_number.required' => 'رقم المركز مطلوب.',
            'name.required' => ' إسم المركز مطلوب.',
        ]);


        $account = new CostCenter();
        $account->code = $validated['code'];
        $account->name = $validated['name'];
        $account->company_id = auth()->user()->model_id;
        $account->save();
        return redirect()->back()->with('success', 'تم إنشاء لمركز بنجاح!');

    }



    public function edit($id)
    {
        $costCenter = CostCenter::findOrFail($id);
        return response()->json($costCenter);
    }

    public function update(Request $request, $id)
    {
        $request->validate(['name' => 'required', 'code' => 'required|unique:cost_centers,code,' . $id]);
        $costCenter = CostCenter::findOrFail($id);
        $costCenter->update($request->all());
        return response()->json(['message' => 'تم تحديث مركز التكلفة بنجاح']);
    }

    public function destroy($id)
    {
        $costCenter = CostCenter::findOrFail($id);
        if ($costCenter->journalEntryDetails()->exists()) {
            return response()->json([
                'message' => 'لا يمكن حذف مركز التكلفة لأنه يحتوي على قيود يومية'
            ], 422); // Use 422 for validation/unprocessable entity
        }
        $costCenter->delete();
        return response()->json(['message' => 'تم حذف مركز التكلفة بنجاح']  , 200);
    }

}
