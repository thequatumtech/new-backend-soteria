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
        Schema::table('cities', function (Blueprint $table) {
            $table->integer('country_id')->nullable()->after('id');
        });
        Schema::table('districts', function (Blueprint $table) {
            $table->integer('city_id')->nullable()->after('id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('cities', function (Blueprint $table) {
            $table->dropColumn('country_id');
        });

        Schema::table('districts', function (Blueprint $table) {
            $table->dropColumn('city_id');
        });
    }
};
