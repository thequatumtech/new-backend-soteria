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
            $table->longText('category_allowed')->nullable()->after('restricted_age_ids');
            $table->longText('sub_category_allowed')->nullable()->after('category_allowed');
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
            $table->dropColumn('category_allowed', 'sub_category_allowed');
        });
    }
};
