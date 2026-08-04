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
        Schema::table('purchase_policy', function (Blueprint $table) {
            $table->float('net_premium')->after('plan_id')->nullable();
            $table->float('fees')->after('net_premium')->nullable();
            $table->float('stamps')->after('fees')->nullable();
            $table->float('sales_tax')->after('stamps')->nullable();
            $table->float('gross_premium')->after('sales_tax')->nullable();
            $table->float('commission_percentage')->after('gross_premium')->nullable();
            $table->float('commission_amount')->after('commission_percentage')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
};
