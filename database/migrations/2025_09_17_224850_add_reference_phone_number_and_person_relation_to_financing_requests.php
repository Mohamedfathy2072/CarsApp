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
            // إضافة العمود reference_phone_number
            $table->string('reference_phone_number')->nullable();

            // إضافة العمود person_relation
            $table->enum('person_relation', ['Mother / Father', 'Son / Daughter', 'Brother / Sister', 'Uncle / Aunt', 'Cousin', 'Other'])->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('financing_requests', function (Blueprint $table) {
            // حذف الأعمدة في حال العودة
            $table->dropColumn('reference_phone_number');
            $table->dropColumn('person_relation');
        });
    }
};
