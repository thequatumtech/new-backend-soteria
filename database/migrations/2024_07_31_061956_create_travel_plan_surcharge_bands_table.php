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
        Schema::create('travel_plan_surcharge_bands', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('travel_plan_id');
            $table->integer('min_age');
            $table->integer('max_age');
            $table->float('surcharge');
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
        Schema::dropIfExists('travel_plan_surcharge_bands');
    }
};
