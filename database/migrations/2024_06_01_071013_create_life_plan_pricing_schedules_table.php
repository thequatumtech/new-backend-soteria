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
        Schema::create('life_plan_pricing_schedules', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('life_plan_id');
            $table->integer('age');
            $table->float('year_1')->default(0);
            $table->float('year_2')->default(0);
            $table->float('year_3')->default(0);
            $table->float('year_4')->default(0);
            $table->float('year_5')->default(0);
            $table->float('year_6')->default(0);
            $table->float('year_7')->default(0);
            $table->float('year_8')->default(0);
            $table->float('year_9')->default(0);
            $table->float('year_10')->default(0);
            $table->float('year_11')->default(0);
            $table->float('year_12')->default(0);
            $table->float('year_13')->default(0);
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
        Schema::dropIfExists('life_plan_pricing_schedules');
    }
};
