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
        Schema::create('supervisor', function (Blueprint $table) {
            $table->id();
            $table->string('svname', 255)->nullable();
            $table->string('svemail', 255)->nullable();
            $table->bigInteger('svmobile_no')->nullable();
            $table->string('sv_gender', 255)->nullable();
            $table->string('joining_date', 255)->nullable();
            $table->bigInteger('nationalandpassportno')->nullable();
            $table->bigInteger('agent_commission')->nullable();
            $table->bigInteger('override_commission')->nullable();
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
        Schema::dropIfExists('supervisor');
    }
};
