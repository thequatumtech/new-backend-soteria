<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('client_motor_insurances', function (Blueprint $table) {
            //
            $table->string('national_id_number')->nullable()->after('plan_id');
            $table->string('residency_number')->nullable()->after('national_id_number');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('client_motor_insurances', function (Blueprint $table) {
            //
            $table->dropColumn(['national_id_number', 'residency_number']);
        });
    }
};
