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
        Schema::create('bills', function (Blueprint $table) {
            $table->id();
            $table->text('number');
            $table->string('type'); // نوع السند: تحويل، عهدة، إيداع
            $table->date('date');
            $table->decimal('amount', 15, 2);
            $table->text('customer');
            $table->text('description');
            $table->unsignedBigInteger('delivery_type_id')->nullable();
            $table->unsignedBigInteger('payment_method_id')->nullable();

            $table->foreign('delivery_type_id')->references('id')->on('delivery_types')->onDelete('set null');
            $table->foreign('payment_method_id')->references('id')->on('payment_methods')->onDelete('set null');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bills');
    }
};
