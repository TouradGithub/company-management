<?php
namespace App\Exports;

use App\Models\Account;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class AccountsExport implements FromCollection, WithHeadings
{
    protected $level;

    public function __construct($level)
    {
        $this->level = $level;
    }

    public function collection()
    {
        $accounts = $this->level === 'all'
            ? Account::where('company_id' , Auth::user()->model_id)->with('children')->where('parent_id' , 0)->get()
            : $this->getAccountsByLevel((int)$this->level);

        $data = collect();

        foreach ($accounts as $account) {
            $data->push([
                'رقم الحساب' => $account->account_number,
                'اسم الحساب' => $account->name,
                'نوع الحساب' => $account->accountType->name,
                'نوع القائمة الختاميه' => $account->closing_list_type == 1?'قائمة الدخل':'الميزانيه العموميه',
                'الرصيد الإفتتاحي' => $account->opening_balance ?? 0,
                'رصيد مدين' => $account->getBalanceDetails() < 0 ? abs($account->getBalanceDetails()) : 0,
                'رصيد دائن' => $account->getBalanceDetails() >= 0 ? $account->getBalanceDetails() : 0,
                'الرصيد' => $account->getBalanceDetails(),
            ]);

            if ($this->level === 'all' && $account->children->count() > 0) {
                foreach ($account->children as $child) {
                    $data->push([
                        'رقم الحساب' => "  " . $child->account_number, // مسافة للتمييز
                        'اسم الحساب' => "  " . $child->name,
                        'نوع الحساب' => $child->accountType->name,
                        'نوع القائمة الختاميه' => $child->closing_list_type == 1?'قائمة الدخل':'الميزانيه العموميه',
                        'الرصيد الإفتتاحي' => $child->opening_balance ?? 0,
                        'رصيد مدين' => $child->getBalanceDetails() < 0 ? abs($child->getBalanceDetails()) : 0,
                        'رصيد دائن' => $child->getBalanceDetails() >= 0 ? $child->getBalanceDetails() : 0,
                        'الرصيد' => $child->getBalanceDetails(),
                    ]);
                }
            }
        }

        return $data;
    }

    public function headings(): array
    {
        return [
            'رقم الحساب',
            'اسم الحساب',
            'نوع الحساب',
            'نوع القائمة الختاميه' ,
            'الرصيد الإفتتاحي',
            'رصيد مدين',
            'رصيد دائن',
            'الرصيد',
        ];
    }

    private function getAccountsByLevel($targetLevel)
    {
        $allAccounts = Account::where('company_id' , Auth::user()->model_id)->with(['children', 'accountType'])->get();
        $filteredAccounts = collect();

        foreach ($allAccounts as $account) {
            $level = $this->calculateLevel($account);
            if ($level == $targetLevel) {
                $filteredAccounts->push($account);
            }
        }

        return $filteredAccounts;
    }

    private function calculateLevel($account, $currentLevel = 1)
    {
        if (!$account->parent_id || $account->parent_id == 0) {
            return $currentLevel;
        }

        $parent = Account::find($account->parent_id);
        if ($parent) {
            return $this->calculateLevel($parent, $currentLevel + 1);
        }

        return $currentLevel;
    }
}
