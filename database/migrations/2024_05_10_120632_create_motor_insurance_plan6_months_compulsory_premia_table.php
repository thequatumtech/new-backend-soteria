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
        Schema::create('motor_insurance_plan_6_months_compulsory_premium', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('motor_insurance_plan_id');
            $table->string('vehicle_type');
            $table->float('premium');
            $table->enum('premium_type',[1,2])->default(2)->comment('1=amount,2=percentage');
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
        Schema::dropIfExists('motor_insurance_plan_6_months_compulsory_premium');
    }
};
