<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Branch;
use App\Models\Payroll;
use App\Models\Category;
use App\Models\Employee;
class PayrollController extends Controller
{
    public function create()
    {
        $branches = Branch::where('company_id',auth()->user()->model_id)->get(); // Assuming you have an Employee model
        return view('campany.payrolls.create', compact('branches'));
    }

    public function index()
    {
        $payrollData = Payroll::with('branch') // لجلب بيانات الفروع
        ->select('date') // تحديد التاريخ كمجال رئيسي
        ->selectRaw('GROUP_CONCAT(DISTINCT branches.name ORDER BY branches.name SEPARATOR ", ") as branch_names') // جمع أسماء الفروع في حقل واحد
        ->selectRaw('COUNT(employee_id) as employees_count') // حساب عدد الموظفين لكل كشف
        ->selectRaw('SUM(net_salary) as total_salary') // حساب إجمالي الراتب لكل كشف
        ->join('branches', 'payrolls.branch_id', '=', 'branches.id') // الربط مع جدول الفروع
        ->groupBy('date') // تجميع حسب التاريخ فقط
        ->orderBy('date', 'desc') // ترتيب من الأحدث إلى الأقدم
        ->get();

        $categories = Category::where('company_id',auth()->user()->model_id)->get();
         // Assuming you have an Employee model
        return view('campany.payrolls.index', compact('payrollData' ,'categories'));
    }

    public function store(request $request)
    {
        $validated = $request->validate([
            'date' => 'required|string',
            'employee' => 'required|array|min:1',   // Ensure employee is an array and not empty
             'branches.*.id' => 'required',
            'employee.*.id' => 'required|integer|exists:employees,id',  // Validate each employee's ID
            'basic_salary' => 'required',  // Ensure basic salary is numeric
            'transportation' => 'nullable',  // Ensure transportation is numeric if present
            'loans' => 'nullable',  // Ensure food is numeric if present
            'food' => 'nullable',  // Ensure food is numeric if present
            'overtime' => 'nullable',  // Ensure overtime is numeric if present
            'deductions' => 'nullable',  // Ensure deductions are numeric if present
            'total' => 'nullable',
        ]);


        foreach ($validated['employee'] as  $index => $empData) {
            $employeeId = $empData['id'];
            $branch = Employee::find($employeeId)->branch->id;

            $payroll = Payroll::
                                where('date', $validated['date'])
                                ->where('employee_id', $employeeId)
                                ->first();
            if ($payroll) {
                // If record exists, update it
                $payroll->update([
                    'branch_id' =>$branch ,
                    'basic_salary' => $validated['basic_salary'][$index]['amount'],
                    'transportation' => $validated['transportation'][$index]['amount'] ?? 0,
                    'food' => $validated['food'][$index]['amount'] ?? 0,
                    'overtime' => $validated['overtime'][$index]['amount'] ?? 0,
                    'deduction' => $validated['deductions'][$index]['amount'] ?? 0,
                    'net_salary' => $validated['total'][$index]['amount'] ?? 0,
                ]);
            } else {
                //return $validatedData;



                Payroll::create([
                    'date' => $validated['date'],
                    'employee_id' => $employeeId,
                    'branch_id' => $branch ,
                    'basic_salary' => $validated['basic_salary'][$index]['amount'],
                    'transportation' => $validated['transportation'][$index]['amount'] ?? 0,
                    'food' => $validated['food'][$index]['amount'] ?? 0,
                    'loans' => $validated['loans'][$index]['amount'] ?? 0,
                    'overtime' => $validated['overtime'][$index]['amount'] ?? 0,
                    'deduction' => $validated['deductions'][$index]['amount'] ?? 0,
                    'net_salary' => $validated['total'][$index]['amount'] ?? 0,
                ]);

                $deductionAmount = $validated['deductions'][$index]['amount'] ?? 0;
                processDeductionPayment($employeeId, $deductionAmount);

                $loanAmount = $validated['loans'][$index]['amount'] ?? 0;
                processLoanPayment($employeeId, $loanAmount);

                $overtime = $validated['overtime'][$index]['amount'] ?? 0;
                processOvertimePayment($employeeId, $overtime);

                getUnpaidOvertimeTotal( $employeeId);
                getUnpaidLoansTotal( $employeeId);
                getUnpaidDeductionsTotal( $employeeId);
            }


        }
        return  redirect()->back()->with(['success' => 'تم اعتماد كشف الرواتب بنجاح']);
    }

    public function getPayrollData(Request $request)
    {
        $monthYear = $request->input('month'); // Format: YYYY-MM
        // $branches = $request->input('branches');


        $query = Payroll::with(['employee', 'branch']);

        if ($monthYear) {
            $query->where('date', $monthYear);
        }

        // if ($branches) {
        //     $query->whereIn('branch_id', $branches);
        // }


        $payrolls = $query->get();
        return response()->json($payrolls);
    }

    public function delete( $id ,$date)
    {

        $payroll= Payroll::find($id);
        $deductionAmount = $payroll->deduction;
        $loanAmount = $payroll->loans;
        $overtime = $payroll->overtime;
        $IdEmployee= $payroll->employee_id;
        reverseDeductionPayment($payroll->employee_id, $deductionAmount);
        reverseLoanPayment($payroll->employee_id, $loanAmount);
        reverseOvertimePayment($payroll->employee_id, $overtime);
        $payroll->delete();
        getUnpaidOvertimeTotal(  $IdEmployee);
        getUnpaidLoansTotal( $IdEmployee);
        getUnpaidDeductionsTotal( $IdEmployee);

        return redirect()->back()->with(['success' => 'تم حذف الكشف بنجاح']);
    }

    // use Mpdf\Mpdf;

    public function exportPdf(Request $request)
    {

        $validated = $request->validate([
            'month' => 'required', // Validate the month
            'branches' => 'nullable|string', // Branch IDs as a comma-separated string
            'categorie' => 'nullable|string', // Branch IDs as a comma-separated string
        ]);
        $categorie = $validated['categorie'];
        $month = $validated['month'];
        $branchIds = explode(',', $validated['branches'] ?? '');
        $payrolls = Payroll::with(['employee', 'branch'])
            ->where('date', $month)
            // ->when(!empty($branchIds), function ($query) use ($branchIds) {
            //     $query->whereIn('branch_id', $branchIds);
            // })
            ->when($categorie && $categorie !== 'all', function ($query) use ($categorie) {
                // Ensure payrolls belong to employees in the selected category
                $query->whereHas('employee', function ($employeeQuery) use ($categorie) {
                    $employeeQuery->where('category_id', $categorie);
                });
            })
            ->get();

            ob_start();
            $mpdf = new \Mpdf\Mpdf();
            $mpdf->SetMargins(10, 10, 20, 20);

            $mpdf->SetAutoPageBreak(TRUE, 20);
            $mpdf->AddPage('P', 'A4');

            $html = view('campany.exports.exportpdf',
                [
                    'month' => $month,
                    'payrolls' => $payrolls,
                ])->render();

            $mpdf->writeHTML($html);
            $mpdf->Output();
            ob_end_flush();
    }
    public function deleteByDate($date)
    {
        $branches = Branch::where('company_id', auth()->user()->model_id)->pluck('id');

        if ($branches->isEmpty()) {
            return redirect()->back()->with('error', 'لا توجد فروع تابعة لهذه الشركة.');
        }

        $payrolls  = Payroll::where('date', $date)
                          ->whereIn('branch_id', $branches)
                          ->get();

        foreach($payrolls as $payroll){
            $deductionAmount = $payroll->deduction;
            $loanAmount = $payroll->loans;
            $IdEmployee= $payroll->employee_id;
            $overtime = $payroll->overtime;
            reverseDeductionPayment($payroll->employee_id, $deductionAmount);
            reverseLoanPayment($payroll->employee_id, $loanAmount);
            reverseOvertimePayment($payroll->employee_id, $overtime);
            $payroll->delete();
            getUnpaidOvertimeTotal(  $IdEmployee);
            getUnpaidLoansTotal( $IdEmployee);
            getUnpaidDeductionsTotal( $IdEmployee);
        }

        return redirect()->back()->with('success', 'تم حذف كشف الرواتب بنجاح!');

    }




}
