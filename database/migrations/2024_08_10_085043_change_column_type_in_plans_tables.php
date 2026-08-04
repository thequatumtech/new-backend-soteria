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
        Schema::table('critical_illness_plans', function (Blueprint $table) {
            $table->string('limit',100)->nullable()->change();
        });
        Schema::table('dental_plans', function (Blueprint $table) {
            $table->string('limit',100)->nullable()->change();
        });
        Schema::table('in_out_patient_plans', function (Blueprint $table) {
            $table->string('limit',100)->nullable()->change();
        });
        Schema::table('in_patient_plans', function (Blueprint $table) {
            $table->string('limit',100)->nullable()->change();
        });
        Schema::table('life_plans', function (Blueprint $table) {
            $table->string('limit',100)->nullable()->change();
        });
        Schema::table('marine_plans', function (Blueprint $table) {
            $table->string('limit',100)->nullable()->change();
        });
        Schema::table('office_plans', function (Blueprint $table) {
            $table->string('limit',100)->nullable()->change();
        });
        Schema::table('personal_accident_plans', function (Blueprint $table) {
            $table->string('limit',100)->nullable()->change();
        });
        Schema::table('pet_plans', function (Blueprint $table) {
            $table->string('limit',100)->nullable()->change();
        });
        Schema::table('travel_plans', function (Blueprint $table) {
            $table->string('limit',100)->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('critical_illness_plans', function (Blueprint $table) {
            $table->float('limit')->change();
        });
        Schema::table('dental_plans', function (Blueprint $table) {
            $table->float('limit')->change();
        });
        Schema::table('in_out_patient_plans', function (Blueprint $table) {
            $table->float('limit')->change();
        });
        Schema::table('in_patient_plans', function (Blueprint $table) {
            $table->float('limit')->change();
        });
        Schema::table('life_plans', function (Blueprint $table) {
            $table->float('limit')->change();
        });
        Schema::table('marine_plans', function (Blueprint $table) {
            $table->float('limit')->change();
        });
        Schema::table('office_plans', function (Blueprint $table) {
            $table->float('limit')->change();
        });
        Schema::table('personal_accident_plans', function (Blueprint $table) {
            $table->float('limit')->change();
        });
        Schema::table('pet_plans', function (Blueprint $table) {
            $table->float('limit')->change();
        });
        Schema::table('travel_plans', function (Blueprint $table) {
            $table->float('limit')->change();
        });
    }
};
