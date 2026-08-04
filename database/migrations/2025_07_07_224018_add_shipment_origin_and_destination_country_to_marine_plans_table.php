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
        Schema::table('marine_plans', function (Blueprint $table) {
            //
            $table->longText('shipment_origin_and_destination_country')->nullable()->after('commission_amount');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('marine_plans', function (Blueprint $table) {
            //
        });
    }
};
