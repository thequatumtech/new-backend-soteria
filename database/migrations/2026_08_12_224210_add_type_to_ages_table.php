<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        Schema::table('ages', function (Blueprint $table) {
            $table->enum('type', ['year', 'month'])->default('year')->after('age');
        });

        DB::table('ages')->update(['type' => 'year']);
    }

    public function down()
    {
        Schema::table('ages', function (Blueprint $table) {
            $table->dropColumn('type');
        });
    }
};
