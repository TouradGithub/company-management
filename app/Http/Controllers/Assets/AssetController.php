<?php

namespace App\Http\Controllers\Assets;

use App\Models\Asset;
use App\Http\Controllers\Controller;
use App\Models\CategoryManagement;
use Illuminate\Http\Request;
use Carbon\Carbon;

class AssetController extends Controller
{

    public function getDepreciationDetails(Request $request)
    {
        $query = Asset::with('categoryManagment');

        if ($request->has('category') && $request->category != '') {
            $query->where('category_management_id', $request->category);
        }

        if ($request->has('search') && $request->search != '') {
            $query->where('assetname', 'like', '%' . $request->search . '%');
        }

        $assets = $query->where('sold', false)->get();
        $results = [];

        foreach ($assets as $asset) {
            $purchaseDate = Carbon::parse($asset->purchasedate);
            $now = Carbon::now();
            $monthsUsed = $now->diffInMonths($purchaseDate);
            $yearsUsed = floor($monthsUsed / 12);
            $remainingMonths = $monthsUsed % 12;

            $originalCost = (float)$asset->originalcost;

            // القيم الثابتة التي ذكرتها
            $lifespan = 6;
            $depreciationRate = 15;
            $salvageValue = $originalCost * 0.10; // 10% من قيمة الشراء

            // حساب الإهلاك السنوي باستخدام النسبة المئوية
            $annualDepreciation = ($originalCost * $depreciationRate) / 100;
            $Depreciationrate = $depreciationRate; // استخدام النسبة المئوية مباشرة كـ Depreciationrate

            $depreciableBase = $originalCost - $salvageValue;

            // حساب مجمع الإهلاك للفترة الفعلية
            $accumulatedDepreciation = 0;
            if ($yearsUsed > 0) {
                $accumulatedDepreciation += min($yearsUsed, $lifespan) * $annualDepreciation;
            }
            $accumulatedDepreciation += ($annualDepreciation / 12) * $remainingMonths;
            $accumulatedDepreciation = min($accumulatedDepreciation, $depreciableBase);

            $bookValue = max($originalCost - $accumulatedDepreciation, $salvageValue);

            $results[] = [
                'id' => $asset->id,
                'name' => $asset->assetname,
                'rate' => number_format($Depreciationrate, 2),
                'original_cost' => number_format($originalCost, 2),
                'salvage_value' => number_format($salvageValue, 2),
                'lifespan' => $lifespan,
                'annual_depreciation' => number_format($annualDepreciation, 2),
                'accumulated_depreciation' => number_format($accumulatedDepreciation, 2),
                'book_value' => number_format($bookValue, 2),
                'purchaseDate' => $asset->purchasedate,
            ];
        }

        return response()->json($results);
    }




    public function create()
    {
        // جلب جميع الفئات
        $categories = CategoryManagement::all();
        return response()->json($categories); // إرجاع الفئات بصيغة JSON
    }

    // حفظ الأصل الجديد عبر Ajax
    public function store(Request $request)
    {
        $request->validate([
            'assetName' => 'required|string',
            'category_management_id' => 'required|exists:category_management,id',
            'purchaseDate' => 'required|date',
            'originalCost' => 'required|numeric',
        ]);

        // إنشاء الأصل
        $asset = Asset::create([
            'assetname' => $request->assetName,
            'category_management_id' => $request->category_management_id,
            'purchasedate' => $request->purchaseDate,
            'originalcost' => $request->originalCost,
        ]);

        return response()->json(['success' => true, 'asset' => $asset]);
    }


    public function getAssets()
    {
        $assets = Asset::with('categoryManagment')->where('sold', false)->get();
        return response()->json($assets);
    }
    public function fetchAssets()
    {
        $assets = Asset::with('categoryManagment')->get();

        $results = [];

        foreach ($assets as $asset) {
            if (!$asset->categoryManagment) {
                continue;
            }

            $purchaseDate = \Carbon\Carbon::parse($asset->purchasedate);
            $yearsUsed = \Carbon\Carbon::now()->diffInYears($purchaseDate);

            // حساب العمر الإنتاجي
            $lifespan = isset($asset->categoryManagment->categorylifespan) ? (int) $asset->categoryManagment->categorylifespan : 0;

            // حساب المعدل
            $rate = (float) $asset->categoryManagment->categoryrate;
            $originalCost = (float) $asset->originalcost;

            // الحساب الجديد للإهلاك السنوي
            $salvageValue = isset($asset->salvageValue) ? $asset->salvageValue : $originalCost * 0.10;  // تعيين القيمة التخريدية الافتراضية

            // حساب الإهلاك السنوي
            $annualDepreciation = ($originalCost - $salvageValue) / $lifespan;

            // حساب مجمع الإهلاك بعد سنتين
            $accumulatedDepreciation = min($yearsUsed, $lifespan) * $annualDepreciation;

            // حساب القيمة الدفترية
            $bookValue = max($originalCost - $accumulatedDepreciation, 0);

            // سجل الإهلاك حسب السنوات
            $depreciations = [];
            $startYear = $purchaseDate->year + 1;  // السنة التي تلي تاريخ الشراء هي سنة بداية الإهلاك
            for ($i = 1; $i <= min($yearsUsed, $lifespan); $i++) {
                $depreciations[] = [
                    'year' => $startYear + $i - 1,  // حساب سنة الإهلاك بشكل صحيح
                    'value' => round($annualDepreciation, 2),
                ];
            }

            $results[] = [
                'id' => $asset->id,
                'assetname' => $asset->assetname,
                'category_managment' => $asset->categoryManagment,
                'purchasedate' => $asset->purchasedate,
                'originalcost' => $originalCost,
                'depreciations' => $depreciations,
                'depreciation_value' => $annualDepreciation,
                'accumulated' => $accumulatedDepreciation,
                'book_value' => $bookValue,
                'salvage_value' => $salvageValue,
                'lifespan' => $lifespan,
                'sold' => $asset->sold,
            ];
        }

        return response()->json($results);
    }


    public function getAsset($id)
    {
        $asset = Asset::with('categoryManagment')->findOrFail($id);
        // التحقق من أن الأصل لم يتم بيعه بالفعل
        if ($asset->sold) {
            return response()->json(['error' => 'الاصل تم بيعه بالفعل'], 400);
        }
        // جلب بيانات الأصل مع حساب الإهلاك والقيمة الدفترية
        if (!$asset->categoryManagment) {
            return response()->json(['error' => 'Asset category not found'], 404);
        }

        // حساب تفاصيل الإهلاك
        $purchaseDate = Carbon::parse($asset->purchasedate);
        $yearsUsed = Carbon::now()->diffInYears($purchaseDate);
        $lifespan = (int)$asset->categoryManagment->categorylifespan;
        $rate = (float)$asset->categoryManagment->categoryrate;
        $originalCost = (float)$asset->originalcost;

        $depreciationValuePerYear = $originalCost * $rate / 100;
        $accumulatedDepreciation = min($yearsUsed, $lifespan) * $depreciationValuePerYear;
        $bookValue = max($originalCost - $accumulatedDepreciation, 0);
        $salvageValue = $originalCost * 0.10; // نسبة ثابتة للقيمة التخريدية 10%

        // إعادة التفاصيل الخاصة بالأصل
        return response()->json([
            'id' => $asset->id,
            'name' => $asset->assetname,
            'original_cost' => $originalCost,
            'purchase_date' => $asset->purchasedate,
            'depreciation_value_per_year' => number_format($depreciationValuePerYear, 2),
            'accumulated_depreciation' => number_format($accumulatedDepreciation, 2),
            'book_value' => number_format($bookValue, 2),
            'lifespan' => $lifespan,
            'salvage_value' => number_format($salvageValue, 2),
        ]);
    }

    public function sellAsset($id, Request $request)
    {
        // جلب الأصل من قاعدة البيانات
        $asset = Asset::with('categoryManagment')->findOrFail($id);

        // التحقق من وجود بيانات الفئة للأصل
        if (!$asset->categoryManagment) {
            return response()->json(['error' => 'Asset category not found'], 404);
        }

        // جلب بيانات البيع من الطلب
        $data = $request->validate([
            'saleDate' => 'required|date',
            'saleAmount' => 'required|numeric',
        ]);

        // حساب تفاصيل الإهلاك
        $purchaseDate = Carbon::parse($asset->purchasedate);
        $yearsUsed = Carbon::now()->diffInYears($purchaseDate);
        $lifespan = (int)$asset->categoryManagment->categorylifespan;
        $rate = (float)$asset->categoryManagment->categoryrate;
        $originalCost = (float)$asset->originalcost;

        // حساب الإهلاك السنوي
        $depreciationValuePerYear = $originalCost * $rate / 100;

        // حساب مجموع الإهلاك التراكمي (باستخدام العمر الافتراضي أو السنوات المستخدمة)
        $accumulatedDepreciation = min($yearsUsed, $lifespan) * $depreciationValuePerYear;

        // حساب القيمة الدفترية (book value)
        $bookValue = max($originalCost - $accumulatedDepreciation, 0);

        // حساب الربح أو الخسارة من البيع
        $gainOrLoss = $data['saleAmount'] - $bookValue;

        // تحديث بيانات البيع
        $asset->sold = true;
        $asset->sale_date = $data['saleDate'];
        $asset->sale_amount = $data['saleAmount'];
        $asset->gain_or_loss = $gainOrLoss;  // حفظ الربح أو الخسارة في الأصل
        $asset->save();

        // إرجاع البيانات في استجابة JSON
        return response()->json([
            'asset' => $asset,
            'gainOrLoss' => $gainOrLoss,
            'bookValue' => $bookValue
        ]);
    }
    public function edit($id)
    {
        $asset = Asset::findOrFail($id);
        return response()->json($asset);
    }
    public function update(Request $request)
    {
        $asset = Asset::findOrFail($request->id);

        $asset->update([
            'assetname' => $request->assetname,
            'category_management_id' => $request->category_management_id,
            'purchasedate' => $request->purchasedate,
            'originalcost' => $request->originalcost,
        ]);

        return response()->json(['status' => 'success']);
    }


}
