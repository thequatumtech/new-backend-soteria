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
        Schema::create('insurance_companies', function (Blueprint $table) {
            $table->id();
            $table->string('company_name', 100);
            $table->string('national_id', 100)->nullable();
            $table->string('register_number', 100)->nullable();
            $table->string('tax_number', 100)->nullable();
            $table->string('email', 100)->nullable();
            $table->string('email_1', 100)->nullable();
            $table->string('email_2', 100)->nullable();
            $table->string('claim_email', 100)->nullable();
            $table->string('mobile_number', 100)->nullable();
            $table->string('telephone_number', 100)->nullable();
            $table->date('joining_date')->nullable();
            $table->integer('country_id')->nullable();
            $table->integer('city_id')->nullable();
            $table->integer('district_id')->nullable();
            $table->string('street_name', 100)->nullable();
            $table->string('building_no', 100)->nullable();
            $table->longText('privacy_policy')->nullable();
            $table->boolean('status')->default(true);
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
        Schema::dropIfExists('insurance_companies');
    }
};
