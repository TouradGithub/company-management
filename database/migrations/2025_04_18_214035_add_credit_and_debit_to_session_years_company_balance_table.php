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
        Schema::table('session_years_company_balance', function (Blueprint $table) {
            $table->decimal('credit', 15, 2)->default(0)->after('balance');
            $table->decimal('debit', 15, 2)->default(0)->after('credit');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('session_years_company_balance', function (Blueprint $table) {
            $table->dropColumn(['credit', 'debit']);
        });
    }
};
