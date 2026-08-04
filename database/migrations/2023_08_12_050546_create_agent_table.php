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
        Schema::create('agent', function (Blueprint $table) {
            $table->id();
            $table->string('agent_full_name', 255)->nullable();
            $table->string('agent_email', 255)->nullable();
            $table->bigInteger('agent_mobile_no')->nullable();
            $table->string('gender', 150)->nullable();
            $table->string('joining_date', 255)->nullable();
            $table->string('nationalidpassport', 255)->nullable();
            $table->string('agentcommision_no_general', 255)->nullable();
            $table->text('agentcommision_no_life')->nullable();
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
        Schema::dropIfExists('agent');
    }
};
