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
        Schema::table('travel_plans', function (Blueprint $table) {
            $table->json('restricted_destination_country_ids')->nullable()->after('restricted_dangerous_activities_ids');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('travel_plans', function (Blueprint $table) {
            //
        });
    }
};
