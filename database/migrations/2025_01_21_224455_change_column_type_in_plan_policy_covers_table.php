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
        Schema::table('office_plan_policy_covers', function (Blueprint $table) {
            $table->string('cover_limit',255)->nullable()->default(null)->change();
            $table->string('cover_deductible',255)->nullable()->default(null)->change();
            $table->string('cover_rate',255)->nullable()->default(null)->change();
        });
        Schema::table('pet_plan_policy_covers', function (Blueprint $table) {
            $table->string('cover_limit',255)->nullable()->default(null)->change();
            $table->string('cover_deductible',255)->nullable()->default(null)->change();
            $table->string('cover_rate',255)->nullable()->default(null)->change();
        });
        Schema::table('marine_plan_policy_covers', function (Blueprint $table) {
            $table->string('cover_limit',255)->nullable()->default(null)->change();
            $table->string('cover_deductible',255)->nullable()->default(null)->change();
            $table->string('cover_rate',255)->nullable()->default(null)->change();
        });
        Schema::table('critical_illness_plan_policy_covers', function (Blueprint $table) {
            $table->string('cover_limit',255)->nullable()->default(null)->change();
            $table->string('cover_deductible',255)->nullable()->default(null)->change();
        });
        Schema::table('travel_plan_policy_covers', function (Blueprint $table) {
            $table->string('cover_limit',255)->nullable()->default(null)->change();
            $table->string('cover_deductible',255)->nullable()->default(null)->change();
        });
        Schema::table('dental_plan_policy_covers', function (Blueprint $table) {
            $table->string('cover_limit',255)->nullable()->default(null)->change();
            $table->string('cover_deductible',255)->nullable()->default(null)->change();
        });
        Schema::table('motor_insurance_plan_policy_covers', function (Blueprint $table) {
            $table->string('cover_limit',255)->nullable()->default(null)->change();
            $table->string('cover_deductible',255)->nullable()->default(null)->change();
        });
        Schema::table('personal_accident_plan_policy_covers', function (Blueprint $table) {
            $table->string('cover_limit',255)->nullable()->default(null)->change();
            $table->string('cover_deductible',255)->nullable()->default(null)->change();
        });
        Schema::table('in_out_patient_plan_policy_covers', function (Blueprint $table) {
            $table->string('cover_limit',255)->nullable()->default(null)->change();
        });
        Schema::table('in_patient_plan_policy_covers', function (Blueprint $table) {
            $table->string('cover_limit',255)->nullable()->default(null)->change();
        });
        Schema::table('life_plan_policy_covers', function (Blueprint $table) {
            $table->string('cover_limit',255)->nullable()->default(null)->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('office_plan_policy_covers', function (Blueprint $table) {
            $table->float('cover_limit')->default(0)->change();
            $table->float('cover_deductible')->default(0)->change();
            $table->float('cover_rate')->default(0)->change();
        });
        Schema::table('marine_plan_policy_covers', function (Blueprint $table) {
            $table->float('cover_limit')->default(0)->change();
            $table->float('cover_deductible')->default(0)->change();
            $table->float('cover_rate')->default(0)->change();
        });
        Schema::table('pet_plan_policy_covers', function (Blueprint $table) {
            $table->float('cover_limit')->default(0)->change();
            $table->float('cover_deductible')->default(0)->change();
            $table->float('cover_rate')->default(0)->change();
        });
        Schema::table('critical_illness_plan_policy_covers', function (Blueprint $table) {
            $table->float('cover_limit')->default(0)->change();
            $table->float('cover_deductible')->default(0)->change();
        });
        Schema::table('travel_plan_policy_covers', function (Blueprint $table) {
            $table->float('cover_limit')->default(0)->change();
            $table->float('cover_deductible')->default(0)->change();
        });
        Schema::table('personal_accident_plan_policy_covers', function (Blueprint $table) {
            $table->float('cover_limit')->default(0)->change();
            $table->float('cover_deductible')->default(0)->change();
        });
        Schema::table('motor_insurance_plan_policy_covers', function (Blueprint $table) {
            $table->float('cover_limit')->default(0)->change();
            $table->float('cover_deductible')->default(0)->change();
        });
        Schema::table('dental_plan_policy_covers', function (Blueprint $table) {
            $table->float('cover_limit')->default(0)->change();
            $table->float('cover_deductible')->default(0)->change();
        });
        Schema::table('in_out_patient_plan_policy_covers', function (Blueprint $table) {
            $table->float('cover_limit')->default(0)->change();
        });
        Schema::table('in_patient_plan_policy_covers', function (Blueprint $table) {
            $table->float('cover_limit')->default(0)->change();
        });
        Schema::table('life_plan_policy_covers', function (Blueprint $table) {
            $table->float('cover_limit')->default(0)->change();
        });
    }
};
