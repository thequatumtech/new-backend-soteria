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
        Schema::create('client_dentals_insurances', function (Blueprint $table) {
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
            $table->string('place_residence')->nullable();
            $table->string('occupancy_work')->nullable();
            $table->bigInteger('city_id')->nullable();
            $table->bigInteger('district_id')->nullable();
            $table->string('street_name')->nullable();
            $table->string('building_no')->nullable();
            $table->string('company_name')->nullable();
            $table->string('position')->nullable();
            $table->string('work_nature')->nullable();
            $table->bigInteger('company_city_id')->nullable();
            $table->bigInteger('company_district_id')->nullable();
            $table->string('company_street_name')->nullable();
            $table->string('company_building_no')->nullable();
            $table->string('company_contact')->nullable();
            $table->string('insurance_limit')->nullable();
            $table->date('inception_date')->nullable();
            $table->date('expiry_date')->nullable();
            $table->string('documents')->nullable();
            $table->bigInteger('plan_id')->nullable();
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
        Schema::dropIfExists('client_dentals_insurances');
    }
};
