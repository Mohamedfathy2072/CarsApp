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
        Schema::table('cars', function (Blueprint $table) {
            $table->string('vehicle_category')->nullable();
            $table->string('description')->nullable();
            $table->enum('payment_option', ['cash', 'installment'])->nullable();


        });
    }

    /**
     * Reverse the migrations.
     */

    public function down(): void
    {
        Schema::table('cars', function (Blueprint $table) {
            $table->dropColumn(['vehicle_category', 'description', 'payment_option']);
        });
    }
};
