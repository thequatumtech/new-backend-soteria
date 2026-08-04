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
        Schema::table('motor_insurance_plan_comprehensive_cover_fees', function (Blueprint $table) {
            $table->float('net_premium')->after('sales_tax');
            $table->float('commission_amount')->after('commission_percentage');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('motor_insurance_plan_comprehensive_cover_fees', function (Blueprint $table) {
            $table->dropColumn(['commission_amount','net_premium']);
        });
    }
};
