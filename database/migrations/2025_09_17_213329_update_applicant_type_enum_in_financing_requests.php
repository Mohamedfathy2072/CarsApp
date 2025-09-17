<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('financing_requests', function (Blueprint $table) {
            $table->enum('applicant_type', ['unemployed', 'employee', 'self-employed'])->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('financing_requests', function (Blueprint $table) {
            $table->enum('applicant_type', ['student', 'employee'])->change();
        });
    }
};
