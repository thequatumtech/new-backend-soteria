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
        Schema::create('motor_insurance_plans', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('insurance_company_id');
            $table->bigInteger('line_of_business_id');
            $table->bigInteger('motor_plan_id');
            $table->string('plan_name',100);
            $table->date('start_date');
            $table->date('end_date');
            $table->longText('insurance_policy_text')->nullable();
            $table->string('insurance_policy_pdf',100)->nullable();
            $table->string('restricted_country_ids',100)->nullable();
            $table->string('restricted_city_ids',100)->nullable();
            $table->string('restricted_district_ids',100)->nullable();
            $table->string('restricted_age_ids',100)->nullable();
            $table->string('restricted_vehicle_type_ids',100)->nullable();
            $table->string('restricted_vehicle_brand_ids',100)->nullable();
            $table->string('restricted_vehicle_category_ids',100)->nullable();
            $table->string('restricted_engine_type_ids',100)->nullable();
            $table->string('claim_deductible_ids',100)->nullable();
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
        Schema::dropIfExists('motor_insurance_plans');
    }
};
