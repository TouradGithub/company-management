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
        Schema::create('session_years_company_balance', function (Blueprint $table) {
            $table->id();
            $table->string('company_id');
            $table->string('account_id');
            $table->decimal('balance', 15, 2);
            $table->string('session_year_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('session_years_company_balance');
    }
};
