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
        Schema::create('pet_insurance', function (Blueprint $table) {
            $table->id();
            $table->string('pet_type',255)->nullable();
            $table->string('owner_name',255)->nullable();
            $table->string('pet_name',255)->nullable();
            $table->bigInteger('pet_age')->nullable();
            $table->string('gender',255)->nullable();
            $table->string('color',255)->nullable();
            $table->string('pri_conditions',255)->nullable();
            $table->timestamp('deleted_at')->nullable();
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
        Schema::dropIfExists('pet_insurance');
    }
};
