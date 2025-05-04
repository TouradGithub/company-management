<?php

namespace App\Imports;

use App\Models\Account;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Concerns\ToCollection;

class ProductsImport implements ToCollection
{
    public function collection(Collection $rows)
    {
//        DB::transaction(function ($rows){
            $rows->shift();
            $companyId = getCompanyId();
            foreach ($rows as $row) {
                $category = Category::where('company_id' , getCompanyId())
                    ->where('name', 'like', '%'.$row[1].'%')->first();
                if(!$category)continue;
                Product::create([
                    'name' => $row[0],
                    'category_id' =>$category->id,
                    'stock' => $row[2],
                    'description' => $row[3],
                    'price' => $row[4],
                    'cost' => $row[5],
                    'min_price' => $row[6],
                    'tax' => $row[7],
                    'image' => $row[8] ?? null,
                    'company_id' =>$companyId ,
                    'created_by' => Auth::user()->name,
                    'branch_id' => 1,
                ]);
            }
//        });
    }
}
