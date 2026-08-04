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
            $table->enum('notify_30_days',[0,1])->comment('0=Pending,1=Sent')->default(0)->after('payment_status');
            $table->enum('notify_15_days',[0,1])->comment('0=Pending,1=Sent')->default(0)->after('notify_30_days');
            $table->enum('custom_notify',[0,1,2])->comment('0=Pending,1=Sent,2=Dont notify')->default(2)->after('notify_15_days');
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
            $table->dropColumn(['notify_30_days','notify_15_days','custom_notify']);
        });
    }
};
