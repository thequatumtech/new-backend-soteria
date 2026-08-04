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
        Schema::create('client_home_insurances', function (Blueprint $table) {
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
            $table->string('place_of_residence')->nullable();
            $table->string('home_type')->nullable();
            $table->string('no_of_floor')->nullable();
            $table->string('no_of_room')->nullable();
            $table->string('size_of_apartment')->nullable();
            $table->string('no_of_residence')->nullable();
            $table->string('home_category')->nullable();
            $table->string('block_no')->nullable();
            $table->string('plate_no')->nullable();
            $table->string('plot_no')->nullable();
            $table->bigInteger('country_id')->nullable();
            $table->bigInteger('city_id')->nullable();
            $table->bigInteger('district_id')->nullable();
            $table->string('street_name')->nullable();
            $table->string('building_no')->nullable();
            $table->string('company_name')->nullable();
            $table->bigInteger('city_id_2')->nullable();
            $table->string('position')->nullable();
            $table->string('work_nature')->nullable();
            $table->longText('previous_policy')->nullable();
            $table->longText('company_declined_to_issue')->nullable();
            $table->longText('claims_accidents_past')->nullable();
            $table->string('protection_system')->nullable();
            $table->string('insurance_limit')->nullable();
            $table->bigInteger('plan_id')->nullable();
            $table->date('effective_date')->nullable();
            $table->date('expiry_date')->nullable();
            $table->string('rent_contract')->nullable();
            $table->string('property_document')->nullable();
            $table->string('content_document')->nullable();
            $table->enum('payment_status',[1,2])->default(2)->comment('1=paid,2=unpaid');
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
        Schema::dropIfExists('client_home_insurances');
    }
};
