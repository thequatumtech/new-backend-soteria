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
        Schema::create('insurance_plans', function (Blueprint $table) {
            $table->id();
            $table->string('company_name', 500)->nullable();
            $table->string('lineofbussines', 500)->nullable();
            $table->string('plan_name', 500)->nullable();
            $table->bigInteger('limit')->nullable();
            $table->bigInteger('plan_fee')->nullable();
            $table->bigInteger('sales_tax')->nullable();
            $table->bigInteger('net_premium')->nullable();
            $table->bigInteger('gross_premium')->nullable();
            $table->string('commission', 500)->nullable();
            $table->bigInteger('stamp_fee')->nullable();
            $table->bigInteger('commission_percent')->nullable();
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
        Schema::dropIfExists('insurance_plans');
    }
};
