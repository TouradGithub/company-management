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
        Schema::create('deductions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('employee_id'); // Foreign key to employees table
            $table->unsignedBigInteger('branch_id');
            $table->date('deduction_date'); // Date of deduction
            $table->integer('deduction_days'); // Number of days for the deduction
            $table->string('deduction_type'); // Type of deduction
            $table->decimal('deduction_value', 10, 2)->nullable(); // Deduction value (nullable for salary_percentage)
            $table->decimal('paid_deduction', 10, 2)->default(0); // Paid deduction value
            $table->decimal('remaining_deduction', 10, 2);
            $table->text('reason')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('deductions');
    }
};
