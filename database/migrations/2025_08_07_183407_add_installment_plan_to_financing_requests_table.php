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
        Schema::table('financing_requests', function (Blueprint $table) {
            $table->integer('installment_plan')->default(12); // بالعدد: 12، 36، 60
        });
    }

    public function down(): void
    {
        Schema::table('financing_requests', function (Blueprint $table) {
            $table->dropColumn('installment_plan');
        });
    }
};
