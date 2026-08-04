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
        Schema::table('in_patient_plan_additional_benefits', function (Blueprint $table) {
            $table->string('benefit_limit')->nullable()->default(null)->change();
        });
        Schema::table('in_out_patient_plan_additional_benefits', function (Blueprint $table) {
            $table->string('benefit_limit')->nullable()->default(null)->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('in_patient_plan_additional_benefits', function (Blueprint $table) {
            $table->float('benefit_limit')->default(0)->change();
        });
        Schema::table('in_out_patient_plan_additional_benefits', function (Blueprint $table) {
            $table->float('benefit_limit')->default(0)->change();
        });
    }
};
