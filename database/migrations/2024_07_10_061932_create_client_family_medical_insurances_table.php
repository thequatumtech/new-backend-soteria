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
        Schema::create('client_family_medical_insurances', function (Blueprint $table) {
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
            $table->string('company_company_contact')->nullable();
            $table->enum('existing_policy_status',[1,2])->default(2)->comment('1=yes,2=no');
            $table->string('existing_policy_company_name')->nullable();
            $table->string('existing_policy_expiry_date')->nullable();
            $table->longText('existing_policy_card')->nullable();
            $table->bigInteger('height')->nullable();
            $table->bigInteger('wight')->nullable();
            $table->bigInteger('chronic_diseases_id')->nullable();
            $table->enum('previous_operation',[1,2])->default(2)->comment('1=yes,2=no');
            $table->string('operation_details')->nullable();
            $table->enum('pregnant_status',[1,2])->default(2)->comment('1=yes,2=no');
            $table->string('pregnant_month')->nullable();
            $table->enum('dangerous_status',[1,2])->default(2)->comment('1=yes,2=no');
            $table->bigInteger('dangerous_id')->nullable();
            $table->string('passport_front_id')->nullable();
            $table->string('passport_back_id')->nullable();
            $table->string('family_book_documents')->nullable();
            $table->string('personal_picture_documents')->nullable();
            $table->string('other_documents')->nullable();
            $table->date('inception_date')->nullable();
            $table->date('expiry_date')->nullable();
            $table->enum('insurance_type',[1,2])->default(1)->comment('1=in patient,2=in out patient');
            $table->string('insurance_class')->nullable();
            $table->bigInteger('inpatient_deductible_id')->nullable();
            $table->bigInteger('outpatient_deductible_id')->nullable();
            $table->bigInteger('no_of_visits_id')->nullable();
            $table->string('insurance_limit')->nullable();
            $table->bigInteger('plan_id')->nullable();
            $table->enum('payment_status',[1,2])->default(2)->comment('1=paid,2=unpaid');
            $table->enum('insurance_type_status',[1,2])->default(2)->comment('1= individual insurance , 2= family insurance');
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
        Schema::dropIfExists('client_family_medical_insurances');
    }
};
