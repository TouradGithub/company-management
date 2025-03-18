<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategoryAccountsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            ['id'=> 1 ,'name' => 'العملاء', 'created_at' => now(), 'updated_at' => now()],
            ['id'=> 2 ,'name' => 'الصندوق', 'created_at' => now(), 'updated_at' => now()],
            ['id'=> 3 ,'name' => 'الموردين', 'created_at' => now(), 'updated_at' => now()],
        ];

        foreach ($categories as $category) {
            DB::table('category_accounts')->updateOrInsert(
                ['id' => $category['id']],
                [
                    'name' => $category['name'],
                    'created_at' => $category['created_at'],
                    'updated_at' => $category['updated_at'],
                ]);
        }
    }

}
