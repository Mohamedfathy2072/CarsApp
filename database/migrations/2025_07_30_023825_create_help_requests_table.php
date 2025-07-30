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
        Schema::create('help_requests', function (Blueprint $table) {
            $table->id();
            $table->string('email')->nullable();
            $table->string('mobile_number');
            $table->enum('type', ['support', 'inquiry', 'lost_account', 'lost_card']);
            $table->string('subject')->nullable();     // يخص support & inquiry
            $table->string('sub_type')->nullable();    // support_type OR inquiry_type
            $table->text('description');
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('help_requests');
    }
};
