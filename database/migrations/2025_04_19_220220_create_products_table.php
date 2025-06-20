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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code')->nullable();
            $table->unsignedBigInteger('category_id');
            $table->unsignedBigInteger('brand_id')->nullable();
            $table->string('barcode')->nullable();
            $table->tinyInteger('status')->default(1); // 1: نشط
            $table->text('description')->nullable();
            $table->decimal('purchase_price', 10, 2);
            $table->decimal('selling_price', 10, 2);
            $table->decimal('discount_price', 10, 2)->nullable();
            $table->decimal('tax_rate', 5, 2)->default(15);
            $table->string('price_unit')->default('piece');
            $table->boolean('has_discount')->default(false);
            $table->string('main_image')->nullable();
            $table->json('additional_images')->nullable();
            $table->integer('stock');
            $table->integer('stock_alert')->default(5);
            $table->integer('transfers')->default(0);
            $table->integer('damages')->default(0);

            $table->unsignedBigInteger('warehouse_id');
            $table->timestamps();
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
