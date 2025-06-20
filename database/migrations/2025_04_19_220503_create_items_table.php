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
        Schema::create('items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bills_id')->constrained()->onDelete('cascade'); // ربط بالحساب البنكي
            $table->text('name');
            $table->decimal('quantity', 15, 2);
            $table->decimal('price', 15, 2);
            $table->text('total');
            $table->string('code')->nullable();
            $table->decimal('tax_rate', 5, 2)->default(0);
            $table->foreignId('product_id')->nullable()->constrained('products')->onDelete('set null');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('items');
    }
};
