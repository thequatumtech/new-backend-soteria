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
        Schema::table('home_plans', function (Blueprint $table) {
            $table->integer('policy_period')->nullable()->after('plan_name');
            $table->dropColumn(['start_date','end_date']);
        });
        Schema::table('critical_illness_plans', function (Blueprint $table) {
            $table->integer('policy_period')->nullable()->after('plan_name');
            $table->dropColumn(['start_date','end_date']);
        });
        Schema::table('dental_plans', function (Blueprint $table) {
            $table->integer('policy_period')->nullable()->after('plan_name');
            $table->dropColumn(['start_date','end_date']);
        });
        Schema::table('in_out_patient_plans', function (Blueprint $table) {
            $table->integer('policy_period')->nullable()->after('plan_name');
            $table->dropColumn(['start_date','end_date']);
        });
        Schema::table('in_patient_plans', function (Blueprint $table) {
            $table->integer('policy_period')->nullable()->after('plan_name');
            $table->dropColumn(['start_date','end_date']);
        });
        Schema::table('life_plans', function (Blueprint $table) {
            $table->integer('policy_period')->nullable()->after('plan_name');
            $table->dropColumn(['start_date','end_date']);
        });
        Schema::table('marine_plans', function (Blueprint $table) {
            $table->integer('policy_period')->nullable()->after('plan_name');
            $table->dropColumn(['start_date','end_date']);
        });
        Schema::table('office_plans', function (Blueprint $table) {
            $table->integer('policy_period')->nullable()->after('plan_name');
            $table->dropColumn(['start_date','end_date']);
        });
        Schema::table('personal_accident_plans', function (Blueprint $table) {
            $table->integer('policy_period')->nullable()->after('plan_name');
//            $table->dropColumn(['start_date','end_date']);
        });
        Schema::table('pet_plans', function (Blueprint $table) {
            $table->integer('policy_period')->nullable()->after('plan_name');
            $table->dropColumn(['start_date','end_date']);
        });
        Schema::table('travel_plans', function (Blueprint $table) {
            $table->integer('policy_period')->nullable()->after('plan_name');
            $table->dropColumn(['start_date','end_date']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('home_plans', function (Blueprint $table) {
            $table->date('start_date')->after('policy_period');
            $table->date('end_date')->after('start_date');
            $table->dropColumn('policy_period');
        });
        Schema::table('critical_illness_plans', function (Blueprint $table) {
            $table->date('start_date')->after('policy_period');
            $table->date('end_date')->after('start_date');
            $table->dropColumn('policy_period');
        });
        Schema::table('dental_plans', function (Blueprint $table) {
            $table->date('start_date')->after('policy_period');
            $table->date('end_date')->after('start_date');
            $table->dropColumn('policy_period');
        });
        Schema::table('in_out_patient_plans', function (Blueprint $table) {
            $table->date('start_date')->after('policy_period');
            $table->date('end_date')->after('start_date');
            $table->dropColumn('policy_period');
        });
        Schema::table('in_patient_plans', function (Blueprint $table) {
            $table->date('start_date')->after('policy_period');
            $table->date('end_date')->after('start_date');
            $table->dropColumn('policy_period');
        });
        Schema::table('life_plans', function (Blueprint $table) {
            $table->date('start_date')->after('policy_period');
            $table->date('end_date')->after('start_date');
            $table->dropColumn('policy_period');
        });
        Schema::table('marine_plans', function (Blueprint $table) {
            $table->date('start_date')->after('policy_period');
            $table->date('end_date')->after('start_date');
            $table->dropColumn('policy_period');
        });
        Schema::table('office_plans', function (Blueprint $table) {
            $table->date('start_date')->after('policy_period');
            $table->date('end_date')->after('start_date');
            $table->dropColumn('policy_period');
        });
        Schema::table('personal_accident_plans', function (Blueprint $table) {
//            $table->date('start_date')->after('policy_period');
//            $table->date('end_date')->after('start_date');
            $table->dropColumn('policy_period');
        });
        Schema::table('pet_plans', function (Blueprint $table) {
            $table->date('start_date')->after('policy_period');
            $table->date('end_date')->after('start_date');
            $table->dropColumn('policy_period');
        });
        Schema::table('travel_plans', function (Blueprint $table) {
            $table->date('start_date')->after('policy_period');
            $table->date('end_date')->after('start_date');
            $table->dropColumn('policy_period');
        });
    }
};
