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
        Schema::create('clients', function (Blueprint $table) {
            $table->id();
            $table->string('first_name',100)->nullable();
            $table->string('father_name',100)->nullable();
            $table->string('grandfather_name',100)->nullable();
            $table->string('surname',100)->nullable();
            $table->enum('language',['en','ar'])->default('en')->comment('en=english,ar=arabic');
            $table->bigInteger('nationality_id');
            $table->string('national_id_number',100)->nullable();
            $table->string('residence_id_number',100)->nullable();
            $table->date('birth_date');
            $table->enum('gender',['1','2'])->default('1')->comment('1=Male,2=Female');
            $table->enum('marital_status',['1','2','3','4'])->default('1')->comment('1=Single,2=Married,3=Divorced,4=Widowed');
            $table->string('email_id')->nullable();
            $table->string('mobile_no')->nullable();
            $table->bigInteger('country_id');
            $table->enum('residing_country_same',['1','2'])->default('1')->comment('1=Yes,2=No');
            $table->bigInteger('residing_country_id');
            $table->bigInteger('city_id');
            $table->bigInteger('district_id');
            $table->string('street_name',100)->nullable();
            $table->string('building_no',100)->nullable();
            $table->string('company_name',100)->nullable();
            $table->bigInteger('occupation_id');
            $table->string('work_nature',100)->nullable();
            $table->bigInteger('company_city_id');
            $table->bigInteger('company_district_id');
            $table->string('company_street_name',100)->nullable();
            $table->string('company_building_no',100)->nullable();
            $table->string('company_contact_no',100)->nullable();
            $table->string('id_front',100)->nullable();
            $table->string('id_back',100)->nullable();
            $table->string('profile_pic',100)->nullable();
            $table->bigInteger('agent_id')->nullable();
            $table->string('password',255)->nullable();
            $table->integer('no_of_policies')->default(0);
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
        Schema::dropIfExists('clients');
    }
};
