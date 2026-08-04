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
        Schema::create('claim_messages', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('claim_id');
            $table->bigInteger('client_id');
            $table->longText('message')->nullable();
            $table->enum('is_message',[0,1])->default(1)->comment('0=status change,1=message');
            $table->enum('sent_by',[0,1])->comment('0=admin,1=client');
            $table->enum('is_read',[0,1])->default(0)->comment('0=unread,1=read');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('claim_messages');
    }
};
