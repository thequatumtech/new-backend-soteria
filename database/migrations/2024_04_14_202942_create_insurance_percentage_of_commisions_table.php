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
        Schema::create('insurance_percentage_of_commisions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('insurance_company_id');
            $table->foreign('insurance_company_id')->references('id')->on('insurance_companies')->onDelete('cascade');
            $table->unsignedBigInteger('line_of_business_id');
            $table->foreign('line_of_business_id')->references('id')->on('line_of_businesses')->onDelete('cascade');
            $table->float('insurance_fee')->nullable();
            $table->float('stamp')->nullable();
            $table->float('tax')->nullable();
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
        Schema::dropIfExists('insurance_percentage_of_commisions');
    }
};
