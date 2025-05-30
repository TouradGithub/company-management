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
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // الاسم
            $table->string('iqamaNumber'); // الاسم
            $table->string('category_id'); // الاسم
            $table->string('branch_id'); // الاسم
            $table->string('job'); // المهنة
            $table->decimal('basic_salary', 10, 2); // الراتب الأساسي
            $table->decimal('housing_allowance', 10, 2); // بدل السكن
            $table->decimal('food_allowance', 10, 2); // بدل الإعاشة
            $table->decimal('transportation_allowance', 10, 2); // بدل المواصلات
            $table->decimal('deducation_total', 10, 2)->default(0); // بدل المواصلات
            $table->decimal('loans_total', 10, 2)->default(0); // بدل المواصلات
            $table->decimal('overtime_total', 10, 2)->default(0); // بدل المواصلات
            $table->string('hire_date'); // بدل المواصلات
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employees');
    }
};
