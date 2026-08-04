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
        Schema::create('in_out_patient_plan_pricing_schedules', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('in_out_patient_plan_id');
            $table->integer('lower_age')->default(0);
            $table->integer('upper_age')->default(0);
            $table->enum('gender',[1,2])->default(1)->comment('1=male,2=female');
            $table->float('vip_class')->default(0);
            $table->float('first_class')->default(0);
            $table->float('second_class')->default(0);
            $table->float('third_class')->default(0);
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
        Schema::dropIfExists('in_out_patient_plan_pricing_schedules');
    }
};
