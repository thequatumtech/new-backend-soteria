<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('in_patient_plan_pricing_schedules', function (Blueprint $table) {
            $table->string('vip_class')->nullable()->change();
            $table->string('first_class')->nullable()->change();
            $table->string('second_class')->nullable()->change();
            $table->string('third_class')->nullable()->change();
        });
    }

    public function down()
    {
        Schema::table('in_patient_plan_pricing_schedules', function (Blueprint $table) {
            $table->double('vip_class')->nullable()->change();
            $table->double('first_class')->nullable()->change();
            $table->double('second_class')->nullable()->change();
            $table->double('third_class')->nullable()->change();
        });
    }
};
