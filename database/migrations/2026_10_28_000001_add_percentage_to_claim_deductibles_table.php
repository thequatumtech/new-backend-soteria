<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('claim_deductibles', function (Blueprint $table) {
            $table->decimal('percentage', 8, 2)->nullable()->after('name');
        });
    }

    public function down()
    {
        Schema::table('claim_deductibles', function (Blueprint $table) {
            $table->dropColumn('percentage');
        });
    }
};
