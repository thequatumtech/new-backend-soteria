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
        Schema::create('automative_insurance', function (Blueprint $table) {
            $table->id();
            $table->string('vahicle_type', 255)->nullable();
            $table->string('vahicle_brand', 255)->nullable();
            $table->string('vahicle_category', 255)->nullable();
            $table->string('vahicle_color', 255)->nullable();
            $table->string('vahicle_reg_no', 255)->nullable();
            $table->string('vahicle_photo', 255)->nullable();
            $table->string('insurance_type', 255)->nullable();
            $table->string('policy_holder', 255)->nullable();
            $table->bigInteger('national_id')->nullable();
            $table->string('chassis_no', 255)->nullable();
            $table->bigInteger('no_of_prev_accident')->nullable();
            $table->bigInteger('no_of_passanger')->nullable();
            $table->string('engine_type', 255)->nullable();
            $table->string('engine_capacity', 255)->nullable();
            $table->string('moto_engine_no', 255)->nullable();
            $table->date('manufacture_date')->nullable();
            $table->bigInteger('total_tickets')->nullable();
            $table->bigInteger('insurance_amount')->nullable();
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
        Schema::dropIfExists('automative_insurance');
    }
};
