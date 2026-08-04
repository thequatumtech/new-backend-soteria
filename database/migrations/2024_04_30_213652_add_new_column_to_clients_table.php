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
        Schema::table('clients', function (Blueprint $table) {
            $table->enum('has_company',[1,2])->default(2)->comment('1=Yes,2=No')->after('no_of_policies');
            $table->enum('is_blacklisted',[1,2])->default(2)->comment('1=Yes,2=No')->after('has_company');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('clients', function (Blueprint $table) {
            $table->dropColumn(['has_company','is_blacklisted']);
        });
    }
};
