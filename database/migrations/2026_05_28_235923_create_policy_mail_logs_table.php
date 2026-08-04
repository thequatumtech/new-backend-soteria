<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('policy_mail_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('client_id');
            $table->unsignedBigInteger('purchase_policy_id');
            $table->longText('client_mail')->nullable();
            $table->longText('agent_mail')->nullable();
            $table->timestamps();

            $table->foreign('client_id')->references('id')->on('clients')->onDelete('cascade');
            $table->foreign('purchase_policy_id')->references('id')->on('purchase_policy')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('policy_mail_logs');
    }
};
