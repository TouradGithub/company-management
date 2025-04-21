<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('accounts', function (Blueprint $table) {
            $table->dropUnique(['account_number']);
            $table->unique(['company_id', 'account_number'], 'accounts_company_account_number_unique');
        });
    }

    public function down(): void
    {
        Schema::table('accounts', function (Blueprint $table) {
            $table->dropUnique('accounts_company_account_number_unique');
            $table->unique('account_number', 'accounts_account_number_unique');
        });
    }
};
