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
        Schema::create('client_companies', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('client_id');
            $table->string('client_company_name',100);
            $table->string('client_company_registered_national_id_no',100);
            $table->string('client_company_registration_no',100);
            $table->bigInteger('client_company_country_id');
            $table->bigInteger('client_company_city_id');
            $table->bigInteger('client_company_district_id');
            $table->string('client_company_street_name',100);
            $table->string('client_company_building_no',100);
            $table->string('client_company_office_no',100);
            $table->string('client_company_telephone_no',100);
            $table->string('client_company_owner_first_name',100);
            $table->string('client_company_owner_father_name',100);
            $table->string('client_company_owner_grandfather_name',100);
            $table->string('client_company_owner_surname',100);
            $table->string('client_company_owner_telephone_no',100);
            $table->enum('is_partner',[1,2])->comment('1=Yes,2=No')->default(2);
            $table->enum('is_authorized',[1,2])->comment('1=Yes,2=No')->default(2);
            $table->string('authorized_position',100)->nullable();
            $table->enum('is_authorization_in_registration',[1,2])->comment('1=Yes,2=No')->default(2);
            $table->string('issuer_authorization_document',100)->nullable();
            $table->string('ownership_document',100)->nullable();
            $table->string('career_municipality_license',100)->nullable();
            $table->string('company_tax_certificate',100)->nullable();
            $table->string('practice_certificate',100)->nullable();
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
        Schema::dropIfExists('client_companies');
    }
};
