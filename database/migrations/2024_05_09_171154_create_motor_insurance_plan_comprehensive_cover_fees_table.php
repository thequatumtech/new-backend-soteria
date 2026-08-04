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
        Schema::create('motor_insurance_plan_comprehensive_cover_fees', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('motor_insurance_plan_id');
            $table->float('fees');
            $table->float('stamps');
            $table->float('sales_tax');
            $table->float('commission_percentage');
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
        Schema::dropIfExists('motor_insurance_plan_comprehensive_cover_fees');
    }
};
