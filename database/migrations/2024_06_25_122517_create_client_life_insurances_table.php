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
        Schema::create('client_life_insurances', function (Blueprint $table) {
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
            $table->string('beneficiary_first_name')->nullable();
            $table->string('beneficiary_last_name')->nullable();
            $table->string('beneficiary_third_name')->nullable();
            $table->string('marital_status')->nullable();
            $table->string('place_residence')->nullable();
            $table->string('occupancy_work')->nullable();
            $table->enum('american_notionality_status',[1,2])->default(2)->comment('1=yes,2=no');
            $table->bigInteger('country_id')->nullable();
            $table->bigInteger('city_id')->nullable();
            $table->bigInteger('district_id')->nullable();
            $table->string('street_name')->nullable();
            $table->string('building_no')->nullable();
            $table->enum('employee_status',[1,2])->default(2)->comment('1=yes,2=no');
            $table->string('company_name')->nullable();
            $table->string('position')->nullable();
            $table->string('work_nature')->nullable();
            $table->bigInteger('employee_city_id')->nullable();
            $table->bigInteger('employee_district_id')->nullable();
            $table->string('employee_street_name')->nullable();
            $table->string('employee_building_no')->nullable();
            $table->bigInteger('company_contact')->nullable();
            $table->bigInteger('height')->nullable();
            $table->bigInteger('wight')->nullable();
            $table->bigInteger('chronic_diseases_id')->nullable();
            $table->enum('previous_operation',[1,2])->default(2)->comment('1=yes,2=no');
            $table->string('operation_details')->nullable();
            $table->enum('company_declined_policy',[1,2])->default(2)->comment('1=yes,2=no');
            $table->string('declined_policy_details')->nullable();
            $table->enum('exiting_life_insur',[1,2])->default(2)->comment('1=yes,2=no');
            $table->string('exiting_life_insur_details')->nullable();
            $table->string('insurance_amount')->nullable();
            $table->date('effective_date')->nullable();
            $table->string('insurance_period')->nullable();
            $table->string('photo_documents')->nullable();
            $table->string('insured_documents')->nullable();
            $table->string('family_book_documents')->nullable();
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
        Schema::dropIfExists('client_life_insurances');
    }
};
