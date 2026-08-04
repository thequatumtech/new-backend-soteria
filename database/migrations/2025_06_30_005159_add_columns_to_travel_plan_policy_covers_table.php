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
        Schema::table('travel_plan_policy_covers', function (Blueprint $table) {
            $table->float('cover_premium')->default(0)->after('cover_deductible_type');
            $table->enum('cover_rate_type', [1, 2])->default(2)->comment('1=amount,2=percentage')->after('cover_premium');
            $table->float('cover_rate')->default(0)->after('cover_rate_type');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('travel_plan_policy_covers', function (Blueprint $table) {
            //
        });
    }
};