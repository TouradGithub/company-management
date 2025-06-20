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
        // database/migrations/xxxx_xx_xx_create_wastes_table.php
        Schema::create('wastes', function (Blueprint $table) {
            $table->id();
            $table->string('waste_number')->unique();
            $table->date('waste_date');
            $table->unsignedBigInteger('branch_id');
            $table->string('reason');
            $table->text('notes')->nullable();
            $table->integer('total_items');
            $table->float('total_quantity');
            $table->decimal('total_cost', 10, 2);
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('wastes');
    }
};
