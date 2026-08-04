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
        Schema::table('client_marine_insurances', function (Blueprint $table) {
            //
            $table->text('trans_shipped_third_country')->nullable()->after('plan_id');
            $table->text('dangerous_activities')->nullable()->after('trans_shipped_third_country');
   
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('client_marine_insurances', function (Blueprint $table) {
            //
            $table->dropColumn(['trans_shipped_third_country', 'dangerous_activities']);
        });
    }
};
