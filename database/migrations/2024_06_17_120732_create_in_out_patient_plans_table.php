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
        Schema::create('in_out_patient_plans', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('insurance_company_id');
            $table->bigInteger('line_of_business_id');
            $table->string('plan_name',100);
            $table->date('start_date');
            $table->date('end_date');
            $table->longText('insurance_policy_text')->nullable();
            $table->string('insurance_policy_pdf',100)->nullable();
            $table->string('restricted_country_ids',100)->nullable();
            $table->string('restricted_city_ids',100)->nullable();
            $table->string('restricted_district_ids',100)->nullable();
            $table->string('restricted_age_ids',100)->nullable();
            $table->string('restricted_occupation_ids',100)->nullable();
            $table->string('restricted_chronic_ids',100)->nullable();
            $table->string('restricted_dangerous_activities_ids',100)->nullable();
            $table->string('in_patient_deductibles_ids',100)->nullable();
            $table->string('out_patient_deductibles_ids',100)->nullable();
            $table->string('no_of_visits_ids',100)->nullable();
            $table->string('medical_networks_ids',100)->nullable();
            $table->float('limit');
            $table->float('net_premium');
            $table->float('fees');
            $table->float('stamps');
            $table->float('sales_tax');
            $table->float('gross_premium');
            $table->float('commission_percentage');
            $table->float('commission_amount');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('in_out_patient_plans');
    }
};
