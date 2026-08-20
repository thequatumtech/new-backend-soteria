<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserSignatureTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_signature', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('purchase_policy_id');
            $table->unsignedBigInteger('client_id');

            $table->string('signature', 255);

            $table->timestamps();

            $table->foreign('purchase_policy_id')
                ->references('id')
                ->on('purchase_policy')
                ->onDelete('cascade');

            $table->foreign('client_id')
                ->references('id')
                ->on('clients')
                ->onDelete('cascade');

            $table->unique([
                'purchase_policy_id',
                'client_id'
            ]);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_signature');
    }
}
