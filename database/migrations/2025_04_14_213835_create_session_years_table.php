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
        Schema::create('session_years', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Example: "2024-2025"
            $table->string('company_id'); // Example: "2024-2025"
            $table->boolean('is_current')->default(false); // For marking the current session
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('session_years');
    }
};
