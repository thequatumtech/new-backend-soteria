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
        Schema::create('client_motor_insurances', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('client_id');
            $table->bigInteger('police_no');
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('third_name')->nullable();
            $table->string('family_name')->nullable();
            $table->string('nationality')->nullable();
            $table->string('nationality_no')->nullable();
            $table->string('id_residence_no')->nullable();
            $table->string('birth_date')->nullable();
            $table->string('gender')->nullable();
            $table->string('marital_status')->nullable();
            $table->string('occupancy')->nullable();
            $table->bigInteger('city_id')->nullable();
            $table->bigInteger('district_id')->nullable();
            $table->string('street_name')->nullable();
            $table->string('building_no')->nullable();
            $table->string('user_mobile_no')->nullable();
            $table->string('company_name')->nullable();
            $table->string('position')->nullable();
            $table->string('work_nature')->nullable();
            $table->string('company_contact_no')->nullable();
            $table->date('inception_date')->nullable();
            $table->date('expiry_date')->nullable();
            $table->bigInteger('no_accident_3_year')->nullable();
            $table->bigInteger('no_ticket_12_month')->nullable();
            $table->bigInteger('no_point_12_month')->nullable();
            $table->string('vahicle_no')->nullable();
            $table->longText('obtain_vahicle_info')->nullable();
            $table->bigInteger('vahicle_type_id')->nullable();
            $table->bigInteger('vahicle_brand_id')->nullable();
            $table->bigInteger('vahicle_category_id')->nullable();
            $table->bigInteger('vahicle_color_id')->nullable();
            $table->string('vahicle_register_no')->nullable();
            $table->string('engine_no')->nullable();
            $table->string('chassis_no')->nullable();
            $table->bigInteger('engine_type_id')->nullable();
            $table->string('engine_capacity')->nullable();
            $table->string('vehicle_manufacturing_date')->nullable();
            $table->string('vehicle_value')->nullable();
            $table->string('insurance_type')->nullable();
            $table->string('residence_id_front')->nullable();
            $table->string('residence_id_back')->nullable();
            $table->string('vehicle_license_front')->nullable();
            $table->string('vehicle_license_back')->nullable();
            $table->string('vehicle_photo_front')->nullable();
            $table->string('vehicle_photo_back')->nullable();
            $table->string('vehicle_photo_right')->nullable();
            $table->string('vehicle_photo_left')->nullable();
            $table->string('carseer_documents')->nullable();
            $table->string('autoscore_documents')->nullable();
            $table->string('customs_declaration')->nullable();
            $table->bigInteger('plan_id')->nullable();
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
        Schema::dropIfExists('client_motor_insurances');
    }
};
