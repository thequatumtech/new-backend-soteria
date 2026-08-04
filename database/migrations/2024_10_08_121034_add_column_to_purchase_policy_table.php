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
        Schema::table('purchase_policy', function (Blueprint $table) {
            $table->timestamp('cancelled_at')->nullable()->after('custom_notify');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('purchase_policy', function (Blueprint $table) {
            $table->dropColumn('cancelled_at');
        });
    }
};
