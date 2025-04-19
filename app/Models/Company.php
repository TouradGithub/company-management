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
        'address' , 'tax_number'
    ];
    public function branches()
    {
        return $this->hasMany(Branch::class, 'company_id');
    }

    public static function createDefaultAccounts($companyId)
    {
        // ١١ الأصول (مدين)
        $assets = Account::create([
            'account_number' => '11',
            'name' => 'الأصول',
            'account_type_id' => 1, // مدين
            'parent_id' => 0,
            'company_id' => $companyId,
            'opening_balance' => 0,
            'closing_list_type' => null,
        ]);

        // ١١١ الأصول المتداولة (مدين)
        $currentAssets = Account::create([
            'account_number' => '111',
            'name' => 'الأصول المتداولة',
            'account_type_id' => 1, // مدين
            'parent_id' => $assets->id,
            'company_id' => $companyId,
            'opening_balance' => 0,
            'closing_list_type' => null,
        ]);

        // الحسابات تحت الأصول المتداولة (مدين)
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

        // ١١٢ الأصول الثابتة (مدين)
        $fixedAssets = Account::create([
            'account_number' => '112',
            'name' => 'الأصول الثابتة',
            'account_type_id' => 1, // مدين
            'parent_id' => $assets->id,
            'company_id' => $companyId,
            'opening_balance' => 0,
            'closing_list_type' => null,
        ]);

        // الحسابات تحت الأصول الثابتة (مدين)
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

        // ١٢ الخصوم (دائن)
        $liabilities = Account::create([
            'account_number' => '12',
            'name' => 'الخصوم',
            'account_type_id' => 2, // دائن
            'parent_id' => 0,
            'company_id' => $companyId,
            'opening_balance' => 0,
            'closing_list_type' => null,
        ]);

        // ١٢١ الخصوم المتداولة (دائن)
        $currentLiabilities = Account::create([
            'account_number' => '121',
            'name' => 'الخصوم المتداولة',
            'account_type_id' => 2, // دائن
            'parent_id' => $liabilities->id,
            'company_id' => $companyId,
            'opening_balance' => 0,
            'closing_list_type' => null,
        ]);

        // الحسابات تحت الخصوم المتداولة (دائن)
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

        // ١٢٢ الخصوم طويلة الأجل (دائن)
        $longTermLiabilities = Account::create([
            'account_number' => '122',
            'name' => 'الخصوم طويلة الأجل',
            'account_type_id' => 2, // دائن
            'parent_id' => $liabilities->id,
            'company_id' => $companyId,
            'opening_balance' => 0,
            'closing_list_type' => null,
        ]);

        // الحسابات تحت الخصوم طويلة الأجل (دائن)
        Account::create([
            'account_number' => '12201',
            'name' => 'القروض',
            'account_type_id' => 2, // دائن
            'parent_id' => $longTermLiabilities->id,
            'company_id' => $companyId,
            'opening_balance' => 0,
            'closing_list_type' => null,
        ]);

        // ١٣ حقوق الملكية (دائن)
        $equity = Account::create([
            'account_number' => '13',
            'name' => 'حقوق الملكية',
            'account_type_id' => 2, // دائن
            'parent_id' => 0,
            'company_id' => $companyId,
            'opening_balance' => 0,
            'closing_list_type' => null,
        ]);

        // الحسابات تحت حقوق الملكية (دائن)
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

        // ١٤ المصروفات (مدين)
        $expenses = Account::create([
            'account_number' => '14',
            'name' => 'المصروفات',
            'account_type_id' => 1, // مدين
            'parent_id' => 0,
            'company_id' => $companyId,
            'opening_balance' => 0,
            'closing_list_type' => null,
        ]);

        // ١٤١ المصاريف الإدارية (مدين)
        Account::create([
            'account_number' => '141',
            'name' => 'المصاريف الإدارية',
            'account_type_id' => 1, // مدين
            'parent_id' => $expenses->id,
            'company_id' => $companyId,
            'opening_balance' => 0,
            'closing_list_type' => null,
        ]);

        // ١٤٢ مصروفات تشغيلية (مدين)
        Account::create([
            'account_number' => '142',
            'name' => 'مصروفات تشغيلية',
            'account_type_id' => 1, // مدين
            'parent_id' => $expenses->id,
            'company_id' => $companyId,
            'opening_balance' => 0,
            'closing_list_type' => null,
        ]);

        // ١٤٣ المصروفات البيعية والتسويقية (مدين)
        Account::create([
            'account_number' => '143',
            'name' => 'المصروفات البيعية والتسويقية',
            'account_type_id' => 1, // مدين
            'parent_id' => $expenses->id,
            'company_id' => $companyId,
            'opening_balance' => 0,
            'closing_list_type' => null,
        ]);

        // ١٥ الإيرادات (دائن)
        $revenues = Account::create([
            'account_number' => '15',
            'name' => 'الإيرادات',
            'account_type_id' => 2, // دائن
            'parent_id' => 0,
            'company_id' => $companyId,
            'opening_balance' => 0,
            'closing_list_type' => null,
        ]);

        // ١٥١ إيرادات النشاط الرئيسي (دائن)
        Account::create([
            'account_number' => '151',
            'name' => 'إيرادات النشاط الرئيسي',
            'account_type_id' => 2, // دائن
            'parent_id' => $revenues->id,
            'company_id' => $companyId,
            'opening_balance' => 0,
            'closing_list_type' => null,
        ]);

        // ١٥٢ إيرادات أخرى (دائن)
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
