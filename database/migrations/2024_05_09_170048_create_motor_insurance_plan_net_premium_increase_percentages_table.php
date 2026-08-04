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
        Schema::create('motor_insurance_plan_net_premium_increase_percentages', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('motor_insurance_plan_id');
            $table->integer('no_of_accidents');
            $table->float('current_year_increase')->default(0);
            $table->float('first_year_increase')->default(0);
            $table->float('second_year_increase')->default(0);
            $table->float('third_year_increase')->default(0);
            $table->float('fourth_year_increase')->default(0);
            $table->float('fifth_year_increase')->default(0);
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
        Schema::dropIfExists('motor_insurance_plan_net_premium_increase_percentages');
    }
};
