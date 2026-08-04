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
        Schema::create('black_list_details', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('client_id');
            $table->float('total_gross_premium_paid')->default(0);
            $table->float('total_net_premium_paid')->default(0);
            $table->date('first_insurance_date')->nullable();
            $table->string('dealt_insurance_companies_id')->nullable();
            $table->string('purchased_insurance_types_id')->nullable();
            $table->string('blocked_insurance_types_id')->nullable();
            $table->string('attachments')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('black_list_details');
    }
};
