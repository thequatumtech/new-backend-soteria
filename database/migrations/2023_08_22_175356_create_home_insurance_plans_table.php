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
        Schema::create('home_insurance_plans', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('customer_id')->nullable();
            $table->string('fname',255)->nullable();
            $table->string('nationality',255)->nullable();
            $table->bigInteger('national_id')->nullable();
            $table->string('coverage', 255)->nullable();
            $table->string('category', 255)->nullable();
            $table->string('sizeofvilla', 255)->nullable();
            $table->string('location', 255)->nullable();
            $table->bigInteger('nooffloors')->nullable();
            $table->bigInteger('noofrooms')->nullable();
            $table->string('homecategory', 255)->nullable();
            $table->date('effectivedate')->nullable();
            $table->date('expirydate')->nullable();
            $table->bigInteger('limit')->nullable();
            $table->bigInteger('BuildingNo')->nullable();
            $table->bigInteger('BlockNo')->nullable();
            $table->bigInteger('PlaateNo')->nullable();
            $table->string('PlotNo', 255)->nullable();
            $table->string('NoResident', 255)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('home_insurance_plans');
    }
};
