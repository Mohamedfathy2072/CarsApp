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
          Schema::create('cars', function (Blueprint $table) {
        $table->id();
        $table->string('brand');                  // Toyota
        $table->string('model');                  // F3
        $table->year('year');                     // 2022
        $table->string('color');                  // Silver
        $table->string('transmission');           // Automatic
        $table->integer('engine_cc');             // 1000
        $table->string('body_type');              // Sedan
        $table->integer('km_driven');             // 55500
        $table->decimal('price', 10, 2);          // 590000.00
        $table->decimal('down_payment', 10, 2);   // 170000.00
        $table->string('license_validity');       // Silver license to 2027
        $table->string('location');               // Nasr City...
        $table->timestamps();
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cars');
    }
};
