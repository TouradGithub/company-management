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
        Schema::create('properties_journal_entries', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('property_id');
            $table->date('date');
            $table->string('description'); // مثل: دفعة إيجار - نقدي
            $table->string('type'); // payment أو rent
            $table->timestamps();

            $table->foreign('property_id')->references('id')->on('properties')->onDelete('cascade');
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('journal_entries');
    }
};
