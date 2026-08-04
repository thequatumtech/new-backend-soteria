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
        Schema::create('family_travel_insurance_members', function (Blueprint $table) {
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
            $table->string('place_residence')->nullable();
            $table->longText('passport_document')->nullable();
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
        Schema::dropIfExists('family_travel_insurance_members');
    }
};
