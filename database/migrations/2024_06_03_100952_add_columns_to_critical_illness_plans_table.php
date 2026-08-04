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
        Schema::table('critical_illness_plans', function (Blueprint $table) {
            $table->string('restricted_occupation_ids',100)->nullable()->after('restricted_age_ids');
            $table->string('restricted_chronic_ids',100)->nullable()->after('restricted_occupation_ids');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('critical_illness_plans', function (Blueprint $table) {
            $table->dropColumn(['restricted_occupation_ids','restricted_chronic_ids']);
        });
    }
};
