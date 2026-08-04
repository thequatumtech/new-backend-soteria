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
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->string('full_name',255)->nullable();
            $table->string('email',255)->nullable();
            $table->string('occupation',255)->nullable();
            $table->string('mobile_no',255)->nullable();
            $table->string('gender',255)->nullable();
            $table->string('address',255)->nullable();
            $table->string('dob',255)->nullable();
            $table->string('housenoandbuildingname',500)->nullable();
            $table->string('street',255)->nullable();
            $table->string('country',255)->nullable();
            $table->string('city',255)->nullable();
            $table->string('state',255)->nullable();
            $table->string('district',255)->nullable();
            $table->text('stamp_of_company')->nullable();
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
        Schema::dropIfExists('customers');
    }
};
