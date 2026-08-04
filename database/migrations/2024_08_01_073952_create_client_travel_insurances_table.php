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
        Schema::create('client_travel_insurances', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('client_id');
            $table->bigInteger('police_no');
            $table->enum('self_family_status',[1,2])->default(1)->comment('1=self,2=family');
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('third_name')->nullable();
            $table->string('family_name')->nullable();
            $table->string('nationality')->nullable();
            $table->string('nationality_no')->nullable();
            $table->string('id_residence_no')->nullable();
            $table->string('birth_date')->nullable();
            $table->string('gender')->nullable();
            $table->string('marital_status')->nullable();
            $table->string('place_residence')->nullable();
            $table->longText('passport_document')->nullable();
            $table->bigInteger('departure_from_country_id')->nullable();
            $table->bigInteger('destination_country_id')->nullable();
            $table->bigInteger('additional_destination_country_id')->nullable();
            $table->bigInteger('geographical_area_id')->nullable();
            $table->date('effective_date')->nullable();
            $table->bigInteger('travel_days')->nullable();
            $table->date('expiry_date')->nullable();
            $table->string('other_documents')->nullable();
            $table->string('insurance_limit')->nullable();
            $table->bigInteger('plan_id')->nullable();
            $table->enum('payment_status',[1,2])->default(2)->comment('1=paid,2=unpaid');
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
        Schema::dropIfExists('client_travel_insurances');
    }
};
