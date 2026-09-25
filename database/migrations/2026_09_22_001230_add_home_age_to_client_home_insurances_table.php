<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('client_home_insurances', function (Blueprint $table) {
            $table->string('home_age')->nullable()->after('protection_system');
        });
    }

    public function down(): void
    {
        Schema::table('client_home_insurances', function (Blueprint $table) {
            $table->dropColumn('home_age');
        });
    }
};
