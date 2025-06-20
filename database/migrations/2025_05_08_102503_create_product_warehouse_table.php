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
        Schema::create('product_warehouse', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('product_id');
            $table->unsignedBigInteger('warehouse_id');
            $table->integer('stock')->default(0); // كمية المنتج في هذا الفرع
            $table->timestamps();

            $table->unique(['product_id', 'warehouse_id']); // كل منتج في كل فرع مرة واحدة فقط

            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
            $table->unsignedInteger('warehouse_id');
            $table->foreign('warehouse_id')->references('id')->on('branches')->onDelete('cascade');
                    });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_warehouse');
    }
};
