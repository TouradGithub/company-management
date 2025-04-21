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
        Schema::table('invoices_and_account_transactions', function (Blueprint $table) {
            Schema::table('invoices', function (Blueprint $table) {
                $table->string('session_year')->after('employee_id');
            });

            Schema::table('account_transactions', function (Blueprint $table) {
                $table->string('session_year')->after('branch_id');
            });
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('invoices_and_account_transactions', function (Blueprint $table) {
            Schema::table('invoices', function (Blueprint $table) {
                $table->dropColumn('session_year');
            });

            Schema::table('account_transactions', function (Blueprint $table) {
                $table->dropColumn('session_year');
            });
        });
    }
};
