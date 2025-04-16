<?php

namespace App\Http\Controllers\Accounting;

use App\Exports\AccountsExport;
use App\Http\Controllers\Controller;
use App\Models\Account;
use App\Models\AccountType;
use App\Models\SessionYear;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Excel;
use Mpdf\Mpdf;


class SessionYearController extends Controller
{

    public  function  getPage()
    {
        return view('financialaccounting.sessionYear.index');
    }

    public function index()
    {
        $session_years = SessionYear::where('company_id', Auth::user()->model_id)->get();
        return response()->json(['session_years' => $session_years]);
    }

    public function store(Request $request)
    {
        if ($request->is_current) {
            SessionYear::where('is_current', true)->update(['is_current' => false]);
        }

        $year = new SessionYear();
        $year->name = $request->name;
        $year->company_id  = Auth::user()->model_id;
        $year->is_current = $request->is_current ? true : false;
        $year->save();

        return response()->json(['success' => true, 'message' => 'تمت الإضافة بنجاح']);
    }

    public function edit($id)
    {
        $session_year = SessionYear::findOrFail($id);
        return response()->json(['session_year' => $session_year]);
    }

    public function update(Request $request, $id)
    {
        if ($request->is_current) {
            SessionYear::where('company_id' , Auth::user()->model_id)->where('is_current', true)->update(['is_current' => false]);
        }

        $session = SessionYear::findOrFail($id);
        $session->update($request->only(['name', 'is_current']));
        return response()->json(['success' => true, 'message' => 'تم التحديث بنجاح']);
    }

    public function destroy($id)
    {

        $session = SessionYear::findOrFail($id);

        if($session->is_current){
            return response()->json(['success' => false, 'message' => 'لا يمكن حذف السنة المالية الحالية']);
        }
        SessionYear::destroy($id);
        return response()->json(['success' => true, 'message' => 'تم الحذف بنجاح']);
    }


}
