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
        Schema::create('vouchers_containers', function (Blueprint $table) {
            $table->id();
            $table->text('number');
            $table->string('type'); // نوع السند: تحويل، عهدة، إيداع
            $table->date('date');
            $table->decimal('amount', 15, 2);
            $table->text('paymentMethod')->nullable();
            $table->text('fromTo')->nullable();
            $table->text('description')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vouchers_containers');
    }
};
