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
        Schema::create('client_office_insurances', function (Blueprint $table) {
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
            $table->string('place_residence')->nullable();
            $table->string('company_name')->nullable();
            $table->string('company_register_national_id')->nullable();
            $table->string('company_register_id')->nullable();
            $table->string('office_type')->nullable();
            $table->string('no_of_floor')->nullable();
            $table->string('no_of_room')->nullable();
            $table->string('size_of_apartment')->nullable();
            $table->string('age_of_apartment')->nullable();
            $table->string('no_of_residence')->nullable();
            $table->string('office_category')->nullable();
            $table->string('block_no')->nullable();
            $table->string('plate_no')->nullable();
            $table->string('plot_no')->nullable();
            $table->date('effective_date')->nullable();
            $table->date('expiry_date')->nullable();
            $table->bigInteger('no_of_employee')->nullable();
            $table->bigInteger('country_id')->nullable();
            $table->bigInteger('city_id')->nullable();
            $table->bigInteger('district_id')->nullable();
            $table->string('street_name')->nullable();
            $table->string('building_no')->nullable();
            $table->string('office_no')->nullable();
            $table->string('company_telephone')->nullable();
            $table->string('company_owner_name')->nullable();
            $table->string('company_owner_telephone')->nullable();
            $table->enum('partner_company_status',[1,2])->default(2)->comment('1=yes,2=no');
            $table->enum('authorized_insurance_police_status',[1,2])->default(2)->comment('1=yes,2=no');
            $table->enum('auth_company_register_status',[1,2])->default(2)->comment('1=yes,2=no');
            $table->enum('provious_insurance_policy',[1,2])->default(2)->comment('1=yes,2=no');
            $table->enum('insurance_declined_issue_status',[1,2])->default(2)->comment('1=yes,2=no');
            $table->enum('claims_5_year_status',[1,2])->default(2)->comment('1=yes,2=no');
            $table->string('protection_system')->nullable();
            $table->string('insurance_limit')->nullable();
            $table->string('insurance_plan')->nullable();
            $table->date('inception_date')->nullable();
            $table->date('insurance_expiry_date')->nullable();
            $table->string('rent_contract_documents')->nullable();
            $table->string('property_photo_documents')->nullable();
            $table->string('contents_documents')->nullable();
            $table->string('policy_issuer_documents')->nullable();
            $table->string('company_owner_documents')->nullable();
            $table->string('career_municipality_license_documents')->nullable();
            $table->string('owner_id_documents')->nullable();
            $table->string('practice_documents')->nullable();
            $table->string('company_tax_certi_documents')->nullable();
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
        Schema::dropIfExists('client_office_insurances');
    }
};
