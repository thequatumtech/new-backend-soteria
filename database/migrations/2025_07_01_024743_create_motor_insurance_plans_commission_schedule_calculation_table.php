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
        Schema::create('motor_insurance_plans_commission_schedule_calculation', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('motor_insurance_plan_id');
            $table->unsignedBigInteger('vehicle_type_id');
            $table->float('from');
            $table->float('to');
            $table->float('commission');
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
        Schema::dropIfExists('motor_insurance_plans_commission_schedule_calculation');
    }
};
