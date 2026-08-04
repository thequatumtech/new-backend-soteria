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
        Schema::table('agent', function (Blueprint $table) {
            $table->bigInteger('nationality_id')->nullable();
            $table->string('residence_no',100)->nullable();
            $table->date('birth_date')->nullable();
            $table->string('marital_status',100)->nullable();
            $table->bigInteger('country_id')->nullable();
            $table->bigInteger('city_id')->nullable();
            $table->bigInteger('district_id')->nullable();
            $table->string('street_name',255)->nullable();
            $table->string('building_no',100)->nullable();
            $table->string('id_front',100)->nullable();
            $table->string('id_back',100)->nullable();
            $table->string('profile_pic',100)->nullable();
            $table->bigInteger('supervisor_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('agent', function (Blueprint $table) {
            $table->dropColumn([
                'nationality_id',
                'residence_no',
                'birth_date',
                'marital_status',
                'country_id',
                'city_id',
                'district_id',
                'street_name',
                'building_no',
                'id_front',
                'id_back',
                'id_back',
                'profile_pic',
                'supervisor_id'
            ]);
        });
    }
};
