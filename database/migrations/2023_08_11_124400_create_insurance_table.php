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
        Schema::create('insurance', function (Blueprint $table) {
            $table->id();
            $table->string('full_name', 255)->nullable();
            $table->string('license_number', 255)->nullable();
            $table->string('address', 500)->nullable();
            $table->bigInteger('telephone_number')->nullable();
            $table->string('post_address', 500)->nullable();
            $table->string('bussiness_line', 255)->nullable();
            $table->string('tax_id', 255)->nullable();
            $table->string('fax_number', 255)->nullable();
            $table->text('stamp_company')->nullable();
            $table->text('signature')->nullable();
            $table->text('letter_head')->nullable();
            $table->timestamp('deleted_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('insurance');
    }
};
