<?php

namespace App\Imports;

use App\Models\Account;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Concerns\ToCollection;

class AccountsImport implements ToCollection
{
    public function collection(Collection $rows)
    {
//       DB::transaction(function ($rows){
           $rows->shift();
           $companyId = getCompanyId();
           $currentYear = getCurrentYear();
           foreach ($rows as $index => $row) {
               $parent = Account::where('company_id', $companyId)
                   ->where('name', 'like', '%' . $row[3] . '%')
                   ->orWhere('code', 'like', '%' . $row[3] . '%')
                   ->first();
               $parentId = $parent ? $parent->id : null;
               $data = [
                   'account_number'     => $row[0],
                   'name'               => $row[1],
                   'account_type_id'    => $row[2]=='دائن'?1:2,
                   'company_id'         => $companyId,
                   'parent_id'          => $parentId??0,
                   'opening_balance'    => $row[4],
                   'closing_list_type'  => $row[5]=='الميزانيه العموميه'?2:1,
                   'islast'             => $row[6] ?? false,
               ];
               if(!Account::where('company_id' ,$companyId )->where('account_number',$data['account_number'])->first()) continue;
               $account =  Account::create($data);
               $account->updateOwnBalance($currentYear);
               $account->updateParentBalanceFromChildren($currentYear);
           }
//       });
    }
}
