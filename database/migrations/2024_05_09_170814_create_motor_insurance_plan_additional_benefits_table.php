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
        Schema::create('motor_insurance_plan_additional_benefits', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('motor_insurance_plan_id');
            $table->string('benefit_name');
            $table->float('benefit_limit')->default(0);
            $table->enum('benefit_limit_type',[1,2])->default(2)->comment('1=amount,2=percentage');
            $table->float('benefit_deductible')->default(0);
            $table->enum('benefit_deductible_type',[1,2])->default(2)->comment('1=amount,2=percentage');
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
        Schema::dropIfExists('motor_insurance_plan_additional_benefits');
    }
};
