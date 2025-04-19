<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Account extends Model
{
    use HasFactory;
    protected $fillable = ['account_number', 'name', 'account_type_id', 'parent_id', 'company_id','opening_balance', 'closing_list_type','islast'];


    public function accountType()
    {
        return $this->belongsTo(AccountType::class);
    }

    public function parentAccount()
    {
        return $this->belongsTo(Account::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(Account::class, 'parent_id');
    }
    public function journalEntriesDetails()
    {
        return $this->belongsTo(JournalEntryDetail::class, 'account_id');
    }

    // العلاقة مع الشركة
    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function transactions()
    {
        return $this->hasMany(AccountTransaction::class, 'account_id');
    }

    public function balance()
    {
        return $this->hasOne(AccountYear::class, 'account_id');
    }

    public function getBalanceDetails()
    {

        $transactions = $this->transactions;
        $currentDebit = $transactions->sum('debit');
        $currentCredit = $transactions->sum('credit');

        $closingBalance = $this->opening_balance + $currentDebit - $currentCredit;

        return $closingBalance;
    }
    public function sessionBalance()
    {
        return $this->hasOne(AccountYear::class, 'account_id')
            ->where('session_year_id', getCurrentYear())
            ->where('company_id', $this->company_id);
    }


    public function getCreditDebit()
    {
        $transactions = $this->transactions;
        $currentDebit = $transactions->sum('debit');
        $currentCredit = $transactions->sum('credit');

        $closingBalance =  $currentDebit - $currentCredit;
        return $closingBalance;
    }
    public function updateParentBalanceFromChildren($sessionYearId)
    {
        $parentAccount = $this->parentAccount;

        if ($parentAccount) {
            // نحصل على أبناء الحساب الأب (أي إخوة هذا الحساب)
            $children = $parentAccount->children;

            $totalBalance = 0;

            foreach ($children as $child) {
                $childBalance = AccountYear::where('account_id', $child->id)
                    ->where('company_id', $child->company_id)
                    ->where('session_year_id', $sessionYearId)
                    ->value('balance');

                $totalBalance += $childBalance ?? 0;
            }

            AccountYear::updateOrCreate(
                [
                    'account_id' => $parentAccount->id,
                    'company_id' => $parentAccount->company_id,
                    'session_year_id' => $sessionYearId
                ],
                [
                    'balance' => $totalBalance
                ]
            );

            // ثم نتابع للأعلى (لأب الأب)
            $parentAccount->updateParentBalanceFromChildren($sessionYearId);
        }
    }
    public function updateOwnBalance($sessionYearId)
    {
        $debit = $this->transactions()->sum('debit');
        $credit = $this->transactions()->sum('credit');
        $balance =   $debit - $credit;
        if($this->islast){
            $balance += $this->opening_balance ;
        }
        AccountYear::updateOrCreate(
            [
                'account_id' => $this->id,
                'company_id' => $this->company_id,
                'session_year_id' => $sessionYearId
            ],
            [ 'balance' => $balance ]
        );
        return $balance;
    }

}
