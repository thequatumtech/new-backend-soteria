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
        Schema::table('agent', function (Blueprint $table) {
            $table->dropColumn(['agent_full_name']);
            $table->string('first_name',100)->nullable()->after('id');
            $table->string('father_name',100)->nullable()->after('first_name');
            $table->string('grandfather_name',100)->nullable()->after('father_name');
            $table->string('surname',100)->nullable()->after('grandfather_name');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('agent', function (Blueprint $table) {
            $table->string('agent_full_name',255)->after('id');
            $table->dropColumn(['first_name','father_name','grandfather_name','surname']);
        });
    }
};
