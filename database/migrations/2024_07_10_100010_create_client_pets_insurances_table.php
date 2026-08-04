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
        Schema::create('client_pets_insurances', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('client_id');
            $table->bigInteger('police_no');
            $table->enum('pets_type',[1,2])->default(1)->comment('1=dog,2=cat');
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('third_name')->nullable();
            $table->string('family_name')->nullable();
            $table->string('nationality_no')->nullable();
            $table->string('id_residence_no')->nullable();
            $table->string('birth_date')->nullable();
            $table->string('pets_name')->nullable();
            $table->string('pets_dob')->nullable();
            $table->string('gender')->nullable();
            $table->string('type_of_pets')->nullable();
            $table->string('breed')->nullable();
            $table->enum('pets_existing_condition_status',[1,2])->default(2)->comment('1=yes,2=no');
            $table->longText('pets_existing_condition')->nullable();
            $table->string('insurance_limit')->nullable();
            $table->date('inception_date')->nullable();
            $table->date('expiry_date')->nullable();
            $table->bigInteger('plan_id')->nullable();
            $table->string('vaccine_document')->nullable();
            $table->string('pets_picture')->nullable();
            $table->string('pets_passport')->nullable();
            $table->string('personal_picture_documents')->nullable();
            $table->string('pets_permit')->nullable();
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
        Schema::dropIfExists('client_pets_insurances');
    }
};
