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
        Schema::create('purchase_policy', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('client_id');
            $table->bigInteger('policy_id');
            $table->bigInteger('plan_id');
            $table->bigInteger('policy_no');
            $table->bigInteger('policy_type')->default(0)->comment('home plan=1,Office plan=2,Life plan=3,CriticalIllness plan=4,Personal Accident  plan=5,In-patient plan=6,in-out patient plan=7,pets plan=8,dental plan=9,Travel = 10,Marine = 11,Motors = 12');
            $table->string('inception_date')->nullable();
            $table->string('expiry_date')->nullable();
            $table->bigInteger('payment_status')->default(0)->comment('0 unpaid or 1 is paid');
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
        Schema::dropIfExists('purchase_policy');
    }
};
