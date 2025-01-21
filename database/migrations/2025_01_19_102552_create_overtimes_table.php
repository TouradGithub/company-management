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
        Schema::create('overtimes', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->integer('employee_id');
            $table->string('branch_id');
            $table->enum('overtime_type', ['fixed', 'hours', 'daily']);
            $table->decimal('fixed_amount', 10, 2)->nullable();
            $table->decimal('hours', 5, 2)->nullable();
            $table->decimal('hour_multiplier', 3, 2)->default(1);
            $table->decimal('days', 5, 2)->nullable();
            $table->decimal('daily_rate', 10, 2)->nullable();
            $table->decimal('total_amount', 10, 2);
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('overtimes');
    }
};
