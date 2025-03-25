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
        Schema::create('account_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('account_id')->constrained()->onDelete('cascade');
            $table->string('transaction_number')->unique();
            $table->date('transaction_date');
            $table->decimal('debit', 15, 2)->default(0);
            $table->decimal('credit', 15, 2)->default(0);
            $table->decimal('balance', 15, 2)->default(0);
            $table->integer('company_id')->index();
            $table->integer('branch_id')->nullable()->index();
            $table->string('description')->nullable();
            $table->foreignId('journal_entry_id')->nullable()->constrained()->onDelete('set null');
            $table->enum('source_type', ['JournalEntry', 'Invoice'])->default('JournalEntry');
            $table->unsignedBigInteger('source_id')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('account_transactions');
    }
};
