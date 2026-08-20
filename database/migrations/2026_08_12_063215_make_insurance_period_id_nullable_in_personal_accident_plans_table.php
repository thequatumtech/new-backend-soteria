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
        Schema::table('personal_accident_plans', function (Blueprint $table) {
            //
              $table->foreignId('insurance_period_id')
                ->nullable()
                ->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('personal_accident_plans', function (Blueprint $table) {
            //
              $table->foreignId('insurance_period_id')
                ->nullable(false)
                ->change();
        });
    }
};
