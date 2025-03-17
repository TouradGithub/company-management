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
        Schema::create('accounts', function (Blueprint $table) {
            $table->id();
            $table->string('account_number')->unique();
            $table->string('name');
            $table->integer('account_type_id');
            $table->integer('parent_id');
            $table->integer('company_id');
            $table->string('opening_date')->nullable();
            $table->decimal('opening_balance', 15, 2)->default(0);
            $table->integer('type_account_register')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('accounts');
    }
};
