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
            ['name' => 'العملاء', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'الصندوق', 'created_at' => now(), 'updated_at' => now()],
        ];


        DB::table('category_accounts')->insert($categories);
    }

}
