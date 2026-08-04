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
        Schema::table('client_personal_accident_insurances', function (Blueprint $table) {
            //
            $table->text('dangerous_field')->nullable()->after('plan_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('client_personal_accident_insurances', function (Blueprint $table) {
            //
            $table->dropColumn('dangerous_field');
        });
    }
};
