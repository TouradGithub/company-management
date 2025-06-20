<?php

namespace App\Http\Controllers\Assets;

use Illuminate\Http\Request;
use App\Models\CategoryManagement;
use App\Models\Asset; // يجب أن تكون لديك موديل يمثل الأصول
use App\Models\Category; // إذا كانت الأصول تحتوي على فئات مرتبطة
use App\Http\Controllers\Controller;
use Carbon\Carbon;

class DepreciationController extends Controller
{
    // إرجاع تفاصيل الإهلاكات مع الفلاتر
    public function getDepreciationDetails(Request $request)
    {
        // جلب المعاملات المبدئية من قاعدة البيانات
        $query = Asset::query();

        // التصفية حسب الفئة
        if ($request->has('category') && $request->category != '') {
            $query->where('category_id', $request->category);
        }

        // البحث عن الأصول
        if ($request->has('search') && $request->search != '') {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        // إرجاع النتيجة
        $assets = $query->get();
        return response()->json($assets);
    }

    // إرجاع جميع الفئات لتصفية الأصول
    public function getDepreciationCategories()
    {
        $categories = CategoryManagement::all(); // يجب أن تكون لديك موديل Category
        return response()->json($categories);
    }

    public function depreciationReport()
    {
        $assets = Asset::with('categoryManagment')->where('sold', false)->get();

        $currentDate = Carbon::now();

        $data = $assets->map(function ($asset) use ($currentDate) {
            $purchaseDate = Carbon::parse($asset->purchasedate);
            $monthsUsed = $currentDate->diffInMonths($purchaseDate);
            $yearsUsed = floor($monthsUsed / 12);
            $remainingMonths = $monthsUsed % 12;

            $originalCost = (float)$asset->originalcost;
            $lifespan = 6; // العمر الإنتاجي الثابت
            $depreciationRate = 15; // نسبة الإهلاك الثابتة
            $salvageValue = $originalCost * 0.10; // القيمة التخريدية ثابتة كنسبة من التكلفة

            // حساب الإهلاك السنوي بناءً على النسبة
            $annualDepreciation = ($originalCost * $depreciationRate) / 100;

            $depreciableBase = $originalCost - $salvageValue;

            // حساب مجمع الإهلاك للفترة الفعلية
            $accumulatedDepreciation = 0;
            if ($yearsUsed > 0) {
                $accumulatedDepreciation += min($yearsUsed, $lifespan) * $annualDepreciation;
            }
            $accumulatedDepreciation += ($annualDepreciation / 12) * $remainingMonths;
            $accumulatedDepreciation = min($accumulatedDepreciation, $depreciableBase);

            $bookValue = max($originalCost - $accumulatedDepreciation, $salvageValue);

            return [
                'asset_name' => $asset->assetname,
                'category_name' => $asset->categoryManagment->name ?? 'غير محدد',
                'rate' => number_format($depreciationRate, 2) . '%', // عرض النسبة المئوية
                'purchase_date' => $asset->purchasedate,
                'original_cost' => number_format($originalCost, 2),
                'scrap_value' => number_format($salvageValue, 2),
                'useful_life' => $lifespan,
                'annual_depreciation' => number_format($annualDepreciation, 2),
                'total_depreciation' => number_format($accumulatedDepreciation, 2),
                'book_value' => number_format($bookValue, 2),
                'daily_entry' => 'قيد محاسبي تلقائي'
            ];
        });

        return response()->json($data);
    }
}
