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
        Schema::create('complaints', function (Blueprint $table) {
            $table->id();
            $table->string('complaint_number')->nullable();
            $table->bigInteger('client_id')->unsigned();
            $table->bigInteger('line_of_business_id')->unsigned()->nullable();
            $table->bigInteger('insurance_company_id')->unsigned()->nullable();
            $table->date('complaint_date')->nullable();
            $table->bigInteger('complaint_status_id')->unsigned();
            $table->text('complaint_message')->nullable();
            $table->string('attachments')->nullable();
            $table->longText('complaint_log')->nullable();
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
        Schema::dropIfExists('complaints');
    }
};
