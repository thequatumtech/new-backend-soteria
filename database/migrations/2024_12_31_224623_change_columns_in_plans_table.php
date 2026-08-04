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
            $table->longText('restricted_country_ids')->nullable()->change();
            $table->longText('restricted_city_ids')->nullable()->change();
            $table->longText('restricted_district_ids')->nullable()->change();
            $table->longText('restricted_age_ids')->nullable()->change();
            $table->longText('restricted_occupation_ids')->nullable()->change();
            $table->longText('restricted_chronic_ids')->nullable()->change();
        });
        Schema::table('dental_plans', function (Blueprint $table) {
            $table->longText('restricted_country_ids')->nullable()->change();
            $table->longText('restricted_city_ids')->nullable()->change();
            $table->longText('restricted_district_ids')->nullable()->change();
            $table->longText('restricted_age_ids')->nullable()->change();
        });
        Schema::table('home_plans', function (Blueprint $table) {
            $table->longText('restricted_country_ids')->nullable()->change();
            $table->longText('restricted_city_ids')->nullable()->change();
            $table->longText('restricted_district_ids')->nullable()->change();
            $table->longText('restricted_age_ids')->nullable()->change();
        });
        Schema::table('in_out_patient_plans', function (Blueprint $table) {
            $table->longText('restricted_country_ids')->nullable()->change();
            $table->longText('restricted_city_ids')->nullable()->change();
            $table->longText('restricted_district_ids')->nullable()->change();
            $table->longText('restricted_age_ids')->nullable()->change();
            $table->longText('restricted_occupation_ids')->nullable()->change();
            $table->longText('restricted_chronic_ids')->nullable()->change();
            $table->longText('restricted_dangerous_activities_ids')->nullable()->change();
            $table->longText('in_patient_deductibles_ids')->nullable()->change();
            $table->longText('out_patient_deductibles_ids')->nullable()->change();
            $table->longText('no_of_visits_ids')->nullable()->change();
            $table->longText('medical_networks_ids')->nullable()->change();
        });
        Schema::table('in_patient_plans', function (Blueprint $table) {
            $table->longText('restricted_country_ids')->nullable()->change();
            $table->longText('restricted_city_ids')->nullable()->change();
            $table->longText('restricted_district_ids')->nullable()->change();
            $table->longText('restricted_age_ids')->nullable()->change();
            $table->longText('restricted_occupation_ids')->nullable()->change();
            $table->longText('restricted_chronic_ids')->nullable()->change();
            $table->longText('restricted_dangerous_activities_ids')->nullable()->change();
            $table->longText('in_patient_deductibles_ids')->nullable()->change();
            $table->longText('medical_networks_ids')->nullable()->change();
        });
        Schema::table('life_plans', function (Blueprint $table) {
            $table->longText('restricted_country_ids')->nullable()->change();
            $table->longText('restricted_city_ids')->nullable()->change();
            $table->longText('restricted_district_ids')->nullable()->change();
            $table->longText('restricted_age_ids')->nullable()->change();
            $table->longText('restricted_occupation_ids')->nullable()->change();
            $table->longText('restricted_chronic_ids')->nullable()->change();
        });
        Schema::table('marine_plans', function (Blueprint $table) {
            $table->longText('restricted_country_ids')->nullable()->change();
            $table->longText('restricted_city_ids')->nullable()->change();
            $table->longText('restricted_district_ids')->nullable()->change();
            $table->longText('restricted_age_ids')->nullable()->change();
            $table->longText('type_of_cover_id')->nullable()->change();
        });
        Schema::table('motor_insurance_plans', function (Blueprint $table) {
            $table->longText('restricted_country_ids')->nullable()->change();
            $table->longText('restricted_city_ids')->nullable()->change();
            $table->longText('restricted_district_ids')->nullable()->change();
            $table->longText('restricted_age_ids')->nullable()->change();
            $table->longText('restricted_vehicle_type_ids')->nullable()->change();
            $table->longText('restricted_vehicle_brand_ids')->nullable()->change();
            $table->longText('restricted_vehicle_category_ids')->nullable()->change();
            $table->longText('restricted_engine_type_ids')->nullable()->change();
            $table->longText('claim_deductible_ids')->nullable()->change();
        });
        Schema::table('office_plans', function (Blueprint $table) {
            $table->longText('restricted_country_ids')->nullable()->change();
            $table->longText('restricted_city_ids')->nullable()->change();
            $table->longText('restricted_district_ids')->nullable()->change();
            $table->longText('restricted_age_ids')->nullable()->change();
        });
        Schema::table('personal_accident_plans', function (Blueprint $table) {
            $table->longText('restricted_country_ids')->nullable()->change();
            $table->longText('restricted_city_ids')->nullable()->change();
            $table->longText('restricted_district_ids')->nullable()->change();
            $table->longText('restricted_age_ids')->nullable()->change();
            $table->longText('restricted_occupation_ids')->nullable()->change();
        });
        Schema::table('pet_plans', function (Blueprint $table) {
            $table->longText('restricted_country_ids')->nullable()->change();
            $table->longText('restricted_city_ids')->nullable()->change();
            $table->longText('restricted_district_ids')->nullable()->change();
            $table->longText('restricted_age_ids')->nullable()->change();
        });
        Schema::table('travel_plans', function (Blueprint $table) {
            $table->longText('restricted_country_ids')->nullable()->change();
            $table->longText('restricted_city_ids')->nullable()->change();
            $table->longText('restricted_district_ids')->nullable()->change();
            $table->longText('restricted_age_ids')->nullable()->change();
            $table->longText('geographical_areas_ids')->nullable()->change();
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
            $table->string('restricted_country_ids',100)->nullable()->change();
            $table->string('restricted_city_ids',100)->nullable()->change();
            $table->string('restricted_district_ids',100)->nullable()->change();
            $table->string('restricted_age_ids',100)->nullable()->change();
            $table->string('restricted_occupation_ids',100)->nullable()->change();
            $table->string('restricted_chronic_ids',100)->nullable()->change();
        });
        Schema::table('dental_plans', function (Blueprint $table) {
            $table->string('restricted_country_ids',100)->nullable()->change();
            $table->string('restricted_city_ids',100)->nullable()->change();
            $table->string('restricted_district_ids',100)->nullable()->change();
            $table->string('restricted_age_ids',100)->nullable()->change();
        });
        Schema::table('home_plans', function (Blueprint $table) {
            $table->string('restricted_country_ids',100)->nullable()->change();
            $table->string('restricted_city_ids',100)->nullable()->change();
            $table->string('restricted_district_ids',100)->nullable()->change();
            $table->string('restricted_age_ids',100)->nullable()->change();
        });
        Schema::table('in_out_patient_plans', function (Blueprint $table) {
            $table->string('restricted_country_ids',100)->nullable()->change();
            $table->string('restricted_city_ids',100)->nullable()->change();
            $table->string('restricted_district_ids',100)->nullable()->change();
            $table->string('restricted_age_ids',100)->nullable()->change();
            $table->string('restricted_occupation_ids',100)->nullable()->change();
            $table->string('restricted_chronic_ids',100)->nullable()->change();
            $table->string('restricted_dangerous_activities_ids',100)->nullable()->change();
            $table->string('in_patient_deductibles_ids',100)->nullable()->change();
            $table->string('out_patient_deductibles_ids',100)->nullable()->change();
            $table->string('no_of_visits_ids',100)->nullable()->change();
            $table->string('medical_networks_ids',100)->nullable()->change();
        });
        Schema::table('in_patient_plans', function (Blueprint $table) {
            $table->string('restricted_country_ids',100)->nullable()->change();
            $table->string('restricted_city_ids',100)->nullable()->change();
            $table->string('restricted_district_ids',100)->nullable()->change();
            $table->string('restricted_age_ids',100)->nullable()->change();
            $table->string('restricted_occupation_ids',100)->nullable()->change();
            $table->string('restricted_chronic_ids',100)->nullable()->change();
            $table->string('restricted_dangerous_activities_ids',100)->nullable()->change();
            $table->string('in_patient_deductibles_ids',100)->nullable()->change();
            $table->string('medical_networks_ids',100)->nullable()->change();
        });
        Schema::table('life_plans', function (Blueprint $table) {
            $table->string('restricted_country_ids',100)->nullable()->change();
            $table->string('restricted_city_ids',100)->nullable()->change();
            $table->string('restricted_district_ids',100)->nullable()->change();
            $table->string('restricted_age_ids',100)->nullable()->change();
            $table->string('restricted_occupation_ids',100)->nullable()->change();
            $table->string('restricted_chronic_ids',100)->nullable()->change();
        });
        Schema::table('marine_plans', function (Blueprint $table) {
            $table->string('restricted_country_ids',100)->nullable()->change();
            $table->string('restricted_city_ids',100)->nullable()->change();
            $table->string('restricted_district_ids',100)->nullable()->change();
            $table->string('restricted_age_ids',100)->nullable()->change();
            $table->string('type_of_cover_id',100)->nullable()->change();
        });
        Schema::table('motor_insurance_plans', function (Blueprint $table) {
            $table->string('restricted_country_ids',100)->nullable()->change();
            $table->string('restricted_city_ids',100)->nullable()->change();
            $table->string('restricted_district_ids',100)->nullable()->change();
            $table->string('restricted_age_ids',100)->nullable()->change();
            $table->string('restricted_vehicle_type_ids',100)->nullable()->change();
            $table->string('restricted_vehicle_brand_ids',100)->nullable()->change();
            $table->string('restricted_vehicle_category_ids',100)->nullable()->change();
            $table->string('restricted_engine_type_ids',100)->nullable()->change();
            $table->string('claim_deductible_ids',100)->nullable()->change();
        });
        Schema::table('office_plans', function (Blueprint $table) {
            $table->string('restricted_country_ids',100)->nullable()->change();
            $table->string('restricted_city_ids',100)->nullable()->change();
            $table->string('restricted_district_ids',100)->nullable()->change();
            $table->string('restricted_age_ids',100)->nullable()->change();
        });
        Schema::table('personal_accident_plans', function (Blueprint $table) {
            $table->string('restricted_country_ids',100)->nullable()->change();
            $table->string('restricted_city_ids',100)->nullable()->change();
            $table->string('restricted_district_ids',100)->nullable()->change();
            $table->string('restricted_age_ids',100)->nullable()->change();
            $table->string('restricted_occupation_ids',100)->nullable()->change();
        });
        Schema::table('pet_plans', function (Blueprint $table) {
            $table->string('restricted_country_ids',100)->nullable()->change();
            $table->string('restricted_city_ids',100)->nullable()->change();
            $table->string('restricted_district_ids',100)->nullable()->change();
            $table->string('restricted_age_ids',100)->nullable()->change();
        });
        Schema::table('travel_plans', function (Blueprint $table) {
            $table->string('restricted_country_ids',100)->nullable()->change();
            $table->string('restricted_city_ids',100)->nullable()->change();
            $table->string('restricted_district_ids',100)->nullable()->change();
            $table->string('restricted_age_ids',100)->nullable()->change();
            $table->string('geographical_areas_ids',100)->nullable()->change();
        });
    }
};
