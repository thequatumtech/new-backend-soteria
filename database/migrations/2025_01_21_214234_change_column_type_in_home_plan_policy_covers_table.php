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
        Schema::table('home_plan_policy_covers', function (Blueprint $table) {
            $table->string('cover_limit',255)->nullable()->default(null)->change();
            $table->string('cover_deductible',255)->nullable()->default(null)->change();
            $table->string('cover_rate',255)->nullable()->default(null)->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('home_plan_policy_covers', function (Blueprint $table) {
            $table->float('cover_limit')->default(0)->change();
            $table->float('cover_deductible')->default(0)->change();
            $table->float('cover_rate')->default(0)->change();
        });
    }
};
