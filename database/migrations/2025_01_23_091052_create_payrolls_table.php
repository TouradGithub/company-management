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
        Schema::create('payrolls', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('employee_id'); // Foreign key for employee
            $table->unsignedBigInteger('branch_id');
            $table->string('date')->nullable(); // Payroll month (e.g., "2025-01")
            $table->decimal('basic_salary', 10, 2)->default(0); // الراتب الأساسي
            $table->decimal('transportation', 10, 2)->default(0); // بدل التنقل
            $table->decimal('food', 10, 2)->default(0); // بدل الإعاشة
            $table->decimal('overtime', 10, 2)->default(0); // الإضافي
            $table->decimal('deduction', 10, 2)->default(0); // الخصومات
            $table->decimal('net_salary', 10, 2)->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payrolls');
    }
};
