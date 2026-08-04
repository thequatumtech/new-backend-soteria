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
        Schema::table('customers', function (Blueprint $table) {
            $table->bigInteger('national_id')->nullable();
            $table->string('road_name', 500)->nullable();
            $table->string('house_no', 255)->nullable();
            $table->string('password')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->bigInteger('national_id')->nullable();
            $table->string('road_name', 500)->nullable();
            $table->string('house_no', 255)->nullable();
            $table->string('password')->nullable();
        });
    }
};
