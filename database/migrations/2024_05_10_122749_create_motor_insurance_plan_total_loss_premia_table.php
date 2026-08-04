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
        Schema::create('motor_insurance_plan_total_loss_premiums', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('motor_insurance_plan_id');
            $table->bigInteger('vehicle_brand_id');
            $table->bigInteger('vehicle_category_id');
            $table->float('insured_value');
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
        Schema::dropIfExists('motor_insurance_plan_total_loss_premiums');
    }
};
