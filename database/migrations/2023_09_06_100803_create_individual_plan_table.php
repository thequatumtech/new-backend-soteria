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
        Schema::create('individual_plan', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('user_id')->nullable();
            $table->string('individual_type')->nullable();
            $table->bigInteger('age')->nullable();
            $table->text('family_data')->nullable();
            $table->bigInteger('policy_holder')->nullable();
            $table->bigInteger('national_id')->nullable();
            $table->bigInteger('id_no')->nullable();
            $table->string('previouse_medical_case')->nullable();
            $table->string('medical_details')->nullable();
            $table->timestamp('deleted_at')->nullable();
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
        Schema::dropIfExists('individual_plan');
    }
};
