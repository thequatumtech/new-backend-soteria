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
        Schema::table('motor_insurance_plan_comprehensive_cover_premiums', function (Blueprint $table) {
            //
            $table->renameColumn('insured_value', 'from');
            $table->decimal('to', 15, 2)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('motor_insurance_plan_comprehensive_cover_premiums', function (Blueprint $table) {
            //
            $table->renameColumn('from', 'insured_value');
            $table->dropColumn('to');
        });
    }
};
