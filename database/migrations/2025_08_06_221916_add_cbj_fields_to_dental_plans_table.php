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
        Schema::table('dental_plans', function (Blueprint $table) {
            //
            $table->decimal('cbj', 10, 2)->nullable()->after('sales_tax');
            $table->decimal('sales_tax_cbj', 10, 2)->nullable()->after('cbj');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('dental_plans', function (Blueprint $table) {
            //
            $table->dropColumn(['cbj', 'sales_tax_cbj']);
        });
    }
};
