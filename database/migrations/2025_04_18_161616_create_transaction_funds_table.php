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
        Schema::create('transaction_funds', function (Blueprint $table) {
            $table->id();
            $table->foreignId('funds_id')->constrained()->onDelete('cascade'); // ربط بالحساب البنكي
            $table->date('date');
            $table->string('type'); // نوع السند: تحويل، عهدة، إيداع
            $table->decimal('amount', 15, 2);
            $table->text('description')->nullable();
            $table->string('voucher_number')->nullable(); // رقم السند
            $table->string('beneficiary')->nullable();    // اسم المستفيد أو المودِع
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaction_funds');
    }
};
