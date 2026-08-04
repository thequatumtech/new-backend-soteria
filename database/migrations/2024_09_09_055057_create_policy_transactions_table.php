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
        Schema::create('policy_transactions', function (Blueprint $table) {
            $table->id();
            $table->string('date')->nullable();
            $table->bigInteger('client_id');
            $table->string('transaction_id');
            $table->bigInteger('purchase_id');
            $table->bigInteger('amount');
            $table->string('payment_type')->nullable();
            $table->bigInteger('payment_status')->default(0)->comment('0 unpaid or 1 is paid');
            $table->longText('full_responce')->nullable();
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
        Schema::dropIfExists('policy_transactions');
    }
};
