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
        Schema::create('assets', function (Blueprint $table) {
            $table->id();
            $table->text('assetname');
            $table->foreignId('category_management_id')->constrained('category_management')->onDelete('cascade');
            $table->text('purchasedate');
            $table->text('originalcost');
            $table->boolean('sold')->default(false); // لإضافة حالة الأصل (مباع أم لا)
            $table->date('sale_date')->nullable(); // تاريخ البيع
            $table->decimal('sale_amount', 10, 2)->nullable(); // قيمة البيع
            $table->decimal('gain_or_loss', 10, 2)->nullable(); // الربح أو الخسارة
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('assets');
    }
};
