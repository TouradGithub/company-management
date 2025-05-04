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
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_number')->unique();
            $table->enum('invoice_type', ['Sales', 'SalesReturn', 'Purchases', 'PurchasesReturn']);
            $table->integer('parent_invoice_id')->nullable();
            $table->date('invoice_date');
            $table->integer('customer_id')->nullable()->index(); // اختياري لفواتير المبيعات ومرتجع المبيعات
            $table->integer('supplier_id')->nullable()->index();
            $table->integer('branch_id');
            $table->integer('company_id');
            $table->string('employee_id');
            $table->decimal('subtotal', 10, 2);
            $table->decimal('discount', 10, 2)->default(0);
            $table->decimal('tax', 10, 2);
            $table->decimal('total', 10, 2);
            $table->string('status', );
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};
