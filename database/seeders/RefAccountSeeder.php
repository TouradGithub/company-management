<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RefAccountSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('ref_account')->truncate();
        DB::table('ref_account')->insert([
            ['id' => 1, 'name' => 'الأصول'],
            ['id' => 2, 'name' => 'الخصوم'],
            ['id' => 3, 'name' => 'حقوق الملكية'],
        ]);
    }
}
