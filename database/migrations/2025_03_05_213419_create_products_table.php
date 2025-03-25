<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // اسم المنتج
            $table->string('category_id'); // التصنيف
            $table->integer('stock'); // الكمية
            $table->text('description')->nullable(); // الوصف
            $table->decimal('price', 10, 2); // سعر البيع
            $table->decimal('cost', 10, 2); // تكلفة الشراء
            $table->decimal('min_price', 10, 2)->nullable(); // الحد الأدنى للسعر
            $table->decimal('tax', 10, 2)->nullable(); // نسبة الضريبة
            $table->string('image')->nullable();
            $table->string('company_id'); // التصنيف
            $table->string('created_by');
            $table->string('branch_id');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
