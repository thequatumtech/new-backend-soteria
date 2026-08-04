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
        Schema::create('insurance_company_documents', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('insurance_id')->nullable();
            $table->foreign('insurance_id')->references('id')->on('insurance_companies')->onDelete('cascade');
            $table->string('ownership_document', 100)->nullable();
            $table->string('municipality_license', 100)->nullable();
            $table->string('practice_certificate', 100)->nullable();
            $table->string('company_tax_certificate', 100)->nullable();
            $table->string('company_stamp', 100)->nullable();
            $table->string('authorized_signature', 100)->nullable();
            $table->string('letterhead', 100)->nullable();
            $table->string('logo', 100)->nullable();
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
        Schema::dropIfExists('insurance_company_documents');
    }
};
