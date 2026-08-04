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
        Schema::create('claims', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('client_id');
            $table->bigInteger('policy_id');
            $table->bigInteger('insurance_company_id');
            $table->string('claim_no');
            $table->integer('policy_type')->nullable()->comment('home plan=1,Office plan=2,Life plan=3,CriticalIllness plan=4,Personal Accident  plan=5,In-patient plan=6,in-out patient plan=7,pets plan=8,dental plan=9,Travel = 10,Marine = 11,Motors = 12');
            $table->date('effective_date')->nullable();
            $table->date('expiry_date')->nullable();
            $table->string('status')->nullable();
            $table->enum('notify_client',[0,1])->default(1)->comment('0=No,1=Yes');
            $table->enum('notify_insurance_company',[0,1])->default(1)->comment('0=No,1=Yes');
            $table->longText('claim_note')->nullable();
            $table->longText('attachments')->nullable();
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
        Schema::dropIfExists('claims');
    }
};
