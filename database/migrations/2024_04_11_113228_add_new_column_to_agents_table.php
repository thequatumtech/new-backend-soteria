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
            $table->string('agent_code',100)->nullable()->after('agent_mobile_no');
            $table->enum('is_active',['1','2'])->default('1')->comment('1=active,2=suspended')->after('agentcommision_no_life');
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
            $table->dropColumn(['agent_code','is_active']);
        });
    }
};
