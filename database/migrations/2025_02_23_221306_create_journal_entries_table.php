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
        Schema::create('journal_entries', function (Blueprint $table) {
            $table->id();
            $table->string('entry_number')->unique();
            $table->date('entry_date');
            $table->integer('branch_id');
            $table->integer('company_id');
            $table->integer('session_year');
            $table->timestamps();
        });
        Schema::create('journal_entry_details', function (Blueprint $table) {
            $table->id();
            $table->integer('journal_entry_id');
            $table->integer('cost_center_id');
            $table->integer('account_id');
            $table->decimal('debit', 15, 2)->default(0);
            $table->decimal('credit', 15, 2)->default(0); // المبلغ الدائن
            $table->string('comment', );
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('journal_entries');
    }
};
