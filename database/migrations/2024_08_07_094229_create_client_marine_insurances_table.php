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
        Schema::create('client_marine_insurances', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('client_id');
            $table->bigInteger('police_no');
            $table->enum('company_status',[1,2])->default(1)->comment('1=INDIVIDUAL,,2=COMPANY');
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('third_name')->nullable();
            $table->string('family_name')->nullable();
            $table->string('nationality')->nullable();
            $table->string('nationality_no')->nullable();
            $table->string('id_residence_no')->nullable();
            $table->string('birth_date')->nullable();
            $table->string('gender')->nullable();
            $table->string('company_name')->nullable();
            $table->string('company_reg_notional_id')->nullable();
            $table->string('company_reg_no')->nullable();
            $table->bigInteger('company_country_id')->nullable();
            $table->bigInteger('company_city_id')->nullable();
            $table->bigInteger('company_district_id')->nullable();
            $table->string('company_street_name')->nullable();
            $table->string('company_building_no')->nullable();
            $table->string('company_office_no')->nullable();
            $table->string('company_contact')->nullable();
            $table->string('owner_first_name')->nullable();
            $table->string('owner_last_name')->nullable();
            $table->string('owner_third_name')->nullable();
            $table->string('owner_family_name')->nullable();
            $table->string('company_owner_contact')->nullable();
            $table->enum('company_partner_status',[1,2])->default(2)->comment('1=yes,2=no');
            $table->enum('company_authorized_status',[1,2])->default(2)->comment('1=yes,2=no');
            $table->string('authorized_positions')->nullable();
            $table->enum('company_register_status',[1,2])->default(1)->comment('1=yes,2=no');
            $table->string('register_document')->nullable();
            $table->bigInteger('vayage_from_id')->nullable();
            $table->bigInteger('through_country_id')->nullable();
            $table->bigInteger('destination_country_id')->nullable();
            $table->string('type_of_transportation')->nullable();
            $table->string('type_of_cover')->nullable();
            $table->bigInteger('item_category_id')->nullable();
            $table->bigInteger('item_subcategory_id')->nullable();
            $table->string('insurance_limit')->nullable();
            $table->string('bill_no')->nullable();
            $table->date('effective_date')->nullable();
            $table->date('expiry_date')->nullable();
            $table->string('insured_items')->nullable();
            $table->enum('existing_policy_status',[1,2])->default(2)->comment('1=yes,2=no');
            $table->longText('existing_policy_desc')->nullable();
            $table->enum('declined_insurance_status',[1,2])->default(2)->comment('1=yes,2=no');
            $table->longText('declined_insurance_desc')->nullable();
            $table->enum('claims_accident_status',[1,2])->default(2)->comment('1=yes,2=no');
            $table->longText('claims_accident_desc')->nullable();
            $table->longText('billing_of_landing_doc')->nullable();
            $table->longText('copy_of_invoice_doc')->nullable();
            $table->longText('insured_id_doc')->nullable();
            $table->longText('policy_issuer_doc')->nullable();
            $table->longText('company_reg_owner_doc')->nullable();
            $table->longText('career_municipality_license_doc')->nullable();
            $table->longText('company_tax_certificate_doc')->nullable();
            $table->longText('practice_certificate_doc')->nullable();
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
        Schema::dropIfExists('client_marine_insurances');
    }
};
