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
        Schema::create('subadmin', function (Blueprint $table) {
            $table->id();
            $table->string('full_name', 255)->nullable();
            $table->bigInteger('mobile_no')->nullable();
            $table->string('admin_id', 255)->nullable();
            $table->string('password', 255)->nullable();
            $table->string('gender', 25)->nullable();
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
        Schema::dropIfExists('subadmin');
    }
};
