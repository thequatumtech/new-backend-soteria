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
        Schema::table('client_travel_insurances', function (Blueprint $table) {
            //
            $table->text('multiple_destination')->nullable()->after('plan_id');
            $table->text('dangerous_activities')->nullable()->after('multiple_destination');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('client_travel_insurances', function (Blueprint $table) {
            //
            $table->dropColumn(['multiple_destination', 'dangerous_activities']);
        });
    }
};
