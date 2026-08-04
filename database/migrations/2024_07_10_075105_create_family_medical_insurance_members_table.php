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
        Schema::create('family_medical_insurance_members', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('client_insurance_id');
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('third_name')->nullable();
            $table->string('family_name')->nullable();
            $table->string('relation')->nullable();
            $table->string('nationality')->nullable();
            $table->string('nationality_no')->nullable();
            $table->string('id_residence_no')->nullable();
            $table->string('birth_date')->nullable();
            $table->string('gender')->nullable();
            $table->string('marital_status')->nullable();
            $table->string('occupancy_work')->nullable();
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
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('family_medical_insurance_members');
    }
};
