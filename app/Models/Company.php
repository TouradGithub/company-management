<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
class Company extends Authenticatable
{
    use HasFactory;
    protected $fillable = [
        'name',
        'email',
        'password',
        'start_date',
        'end_date',
        'status',
        'address' , 'tax_number','phone_number'
    ];
    public function branches()
    {
        return $this->hasMany(Branch::class, 'company_id');
    }

    public static function createDefaultAccounts($companyId)
    {
        $assets = Account::create([
            'account_number' => '11',
            'name' => 'الأصول',
            'account_type_id' => 1, // مدين
            'parent_id' => 0,
            'company_id' => $companyId,
            'opening_balance' => 0,
            'closing_list_type' => null,
        ]);
        $currentAssets = Account::create([
            'account_number' => '111',
            'name' => 'الأصول المتداولة',
            'account_type_id' => 1, // مدين
            'parent_id' => $assets->id,
            'company_id' => $companyId,
            'opening_balance' => 0,
            'closing_list_type' => null,
        ]);
        Account::create([
            'account_number' => '11101',
            'name' => 'البنك',
            'account_type_id' => 1, // مدين
            'parent_id' => $currentAssets->id,
            'company_id' => $companyId,
            'opening_balance' => 0,
            'closing_list_type' => null,
        ]);
        Account::create([
            'account_number' => '11102',
            'name' => 'الصندوق',
            'account_type_id' => 1, // مدين
            'parent_id' => $currentAssets->id,
            'company_id' => $companyId,
            'opening_balance' => 0,
            'closing_list_type' => null,
        ]);
        Account::create([
            'account_number' => '11103',
            'name' => 'المدينون',
            'account_type_id' => 1, // مدين
            'parent_id' => $currentAssets->id,
            'company_id' => $companyId,
            'opening_balance' => 0,
            'closing_list_type' => null,
        ]);
        Account::create([
            'account_number' => '11104',
            'name' => 'العملاء',
            'account_type_id' => 1, // مدين
            'parent_id' => $currentAssets->id,
            'company_id' => $companyId,
            'opening_balance' => 0,
            'closing_list_type' => null,
        ]);
        Account::create([
            'account_number' => '11105',
            'name' => 'المخزون',
            'account_type_id' => 1, // مدين
            'parent_id' => $currentAssets->id,
            'company_id' => $companyId,
            'opening_balance' => 0,
            'closing_list_type' => null,
        ]);
        Account::create([
            'account_number' => '11106',
            'name' => 'دفعات مقدمة',
            'account_type_id' => 1, // مدين
            'parent_id' => $currentAssets->id,
            'company_id' => $companyId,
            'opening_balance' => 0,
            'closing_list_type' => null,
        ]);
        $fixedAssets = Account::create([
            'account_number' => '112',
            'name' => 'الأصول الثابتة',
            'account_type_id' => 1, // مدين
            'parent_id' => $assets->id,
            'company_id' => $companyId,
            'opening_balance' => 0,
            'closing_list_type' => null,
        ]);
        Account::create([
            'account_number' => '11201',
            'name' => 'المباني',
            'account_type_id' => 1, // مدين
            'parent_id' => $fixedAssets->id,
            'company_id' => $companyId,
            'opening_balance' => 0,
            'closing_list_type' => null,
        ]);
        Account::create([
            'account_number' => '11202',
            'name' => 'السيارات',
            'account_type_id' => 1, // مدين
            'parent_id' => $fixedAssets->id,
            'company_id' => $companyId,
            'opening_balance' => 0,
            'closing_list_type' => null,
        ]);
        Account::create([
            'account_number' => '11203',
            'name' => 'الأثاث',
            'account_type_id' => 1, // مدين
            'parent_id' => $fixedAssets->id,
            'company_id' => $companyId,
            'opening_balance' => 0,
            'closing_list_type' => null,
        ]);
        $liabilities = Account::create([
            'account_number' => '12',
            'name' => 'الخصوم',
            'account_type_id' => 2, // دائن
            'parent_id' => 0,
            'company_id' => $companyId,
            'opening_balance' => 0,
            'closing_list_type' => null,
        ]);
        $currentLiabilities = Account::create([
            'account_number' => '121',
            'name' => 'الخصوم المتداولة',
            'account_type_id' => 2, // دائن
            'parent_id' => $liabilities->id,
            'company_id' => $companyId,
            'opening_balance' => 0,
            'closing_list_type' => null,
        ]);
        Account::create([
            'account_number' => '12101',
            'name' => 'الدائنون',
            'account_type_id' => 2, // دائن
            'parent_id' => $currentLiabilities->id,
            'company_id' => $companyId,
            'opening_balance' => 0,
            'closing_list_type' => null,
        ]);
        Account::create([
            'account_number' => '12102',
            'name' => 'رواتب مستحقة',
            'account_type_id' => 2, // دائن
            'parent_id' => $currentLiabilities->id,
            'company_id' => $companyId,
            'opening_balance' => 0,
            'closing_list_type' => null,
        ]);
        $longTermLiabilities = Account::create([
            'account_number' => '122',
            'name' => 'الخصوم طويلة الأجل',
            'account_type_id' => 2, // دائن
            'parent_id' => $liabilities->id,
            'company_id' => $companyId,
            'opening_balance' => 0,
            'closing_list_type' => null,
        ]);
        Account::create([
            'account_number' => '12201',
            'name' => 'القروض',
            'account_type_id' => 2, // دائن
            'parent_id' => $longTermLiabilities->id,
            'company_id' => $companyId,
            'opening_balance' => 0,
            'closing_list_type' => null,
        ]);
        $equity = Account::create([
            'account_number' => '13',
            'name' => 'حقوق الملكية',
            'account_type_id' => 2, // دائن
            'parent_id' => 0,
            'company_id' => $companyId,
            'opening_balance' => 0,
            'closing_list_type' => null,
        ]);
        Account::create([
            'account_number' => '1301',
            'name' => 'رأس المال',
            'account_type_id' => 2, // دائن
            'parent_id' => $equity->id,
            'company_id' => $companyId,
            'opening_balance' => 0,
            'closing_list_type' => null,
        ]);
        Account::create([
            'account_number' => '1302',
            'name' => 'جاري الشركاء',
            'account_type_id' => 2, // دائن
            'parent_id' => $equity->id,
            'company_id' => $companyId,
            'opening_balance' => 0,
            'closing_list_type' => null,
        ]);
        Account::create([
            'account_number' => '1303',
            'name' => 'أرباح وخسائر مرحلة',
            'account_type_id' => 2, // دائن
            'parent_id' => $equity->id,
            'company_id' => $companyId,
            'opening_balance' => 0,
            'closing_list_type' => null,
        ]);
        $expenses = Account::create([
            'account_number' => '14',
            'name' => 'المصروفات',
            'account_type_id' => 1, // مدين
            'parent_id' => 0,
            'company_id' => $companyId,
            'opening_balance' => 0,
            'closing_list_type' => null,
        ]);
        Account::create([
            'account_number' => '141',
            'name' => 'المصاريف الإدارية',
            'account_type_id' => 1, // مدين
            'parent_id' => $expenses->id,
            'company_id' => $companyId,
            'opening_balance' => 0,
            'closing_list_type' => null,
        ]);
        Account::create([
            'account_number' => '142',
            'name' => 'مصروفات تشغيلية',
            'account_type_id' => 1, // مدين
            'parent_id' => $expenses->id,
            'company_id' => $companyId,
            'opening_balance' => 0,
            'closing_list_type' => null,
        ]);
        Account::create([
            'account_number' => '143',
            'name' => 'المصروفات البيعية والتسويقية',
            'account_type_id' => 1, // مدين
            'parent_id' => $expenses->id,
            'company_id' => $companyId,
            'opening_balance' => 0,
            'closing_list_type' => null,
        ]);
        $revenues = Account::create([
            'account_number' => '15',
            'name' => 'الإيرادات',
            'account_type_id' => 2, // دائن
            'parent_id' => 0,
            'company_id' => $companyId,
            'opening_balance' => 0,
            'closing_list_type' => null,
        ]);
        Account::create([
            'account_number' => '151',
            'name' => 'إيرادات النشاط الرئيسي',
            'account_type_id' => 2, // دائن
            'parent_id' => $revenues->id,
            'company_id' => $companyId,
            'opening_balance' => 0,
            'closing_list_type' => null,
        ]);
        Account::create([
            'account_number' => '152',
            'name' => 'إيرادات أخرى',
            'account_type_id' => 2, // دائن
            'parent_id' => $revenues->id,
            'company_id' => $companyId,
            'opening_balance' => 0,
            'closing_list_type' => null,
        ]);
    }

}
