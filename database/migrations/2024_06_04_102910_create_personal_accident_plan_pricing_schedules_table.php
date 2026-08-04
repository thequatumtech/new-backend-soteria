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
        Schema::create('personal_accident_plan_pricing_schedules', function (Blueprint $table) {
            $table->id();
            $table->integer('age');
            $table->float('m_3')->default(0);
            $table->float('m_6')->default(0);
            $table->float('m_9')->default(0);
            $table->float('m_12')->default(0);
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
        Schema::dropIfExists('personal_accident_plan_pricing_schedules');
    }
};
