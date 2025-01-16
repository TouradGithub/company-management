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
        Schema::create('companies', function (Blueprint $table) {
            $table->id(); // Primary key
            $table->string('name'); // Company name
            $table->string('email')->unique(); // Email address (unique)
            $table->string('password'); // Password
            $table->date('start_date'); // Start date
            $table->date('end_date'); // End date
            $table->enum('status', ['active', 'inactive'])->default('active'); // Status
            $table->timestamps(); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('companies');
    }
};
