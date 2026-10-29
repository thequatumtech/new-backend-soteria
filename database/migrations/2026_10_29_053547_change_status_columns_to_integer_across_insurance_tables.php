<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('client_marine_insurances', function (Blueprint $table) {
            $table->integer('existing_policy_status')->default(0)->comment('1=yes,0=no')->change();
            $table->integer('declined_insurance_status')->default(0)->comment('1=yes,0=no')->change();
            $table->integer('claims_accident_status')->default(0)->comment('1=yes,0=no')->change();
        });

        Schema::table('client_pets_insurances', function (Blueprint $table) {
            $table->integer('pets_existing_condition_status')->default(0)->comment('1=yes,0=no')->change();
        });

        Schema::table('family_medical_insurance_members', function (Blueprint $table) {
            $table->integer('previous_operation')->default(0)->comment('1=yes,0=no')->change();
            $table->integer('pregnant_status')->default(0)->comment('1=yes,0=no')->change();
            $table->integer('dangerous_status')->default(0)->comment('1=yes,0=no')->change();
        });

        Schema::table('client_family_medical_insurances', function (Blueprint $table) {
            $table->integer('existing_policy_status')->default(0)->comment('1=yes,0=no')->change();
            $table->integer('previous_operation')->default(0)->comment('1=yes,0=no')->change();
            $table->integer('pregnant_status')->default(0)->comment('1=yes,0=no')->change();
            $table->integer('dangerous_status')->default(0)->comment('1=yes,0=no')->change();
        });

        Schema::table('critical_illness_insurances', function (Blueprint $table) {
            $table->integer('previous_operation')->default(0)->comment('1=yes,0=no')->change();
            $table->integer('previous_insurance_policy')->default(0)->comment('1=yes,0=no')->change();
        });


        Schema::table('client_life_insurances', function (Blueprint $table) {
            $table->integer('previous_operation')->default(0)->comment('1=yes,0=no')->change();
            $table->integer('company_declined_policy')->default(0)->comment('1=yes,0=no')->change();
            $table->integer('exiting_life_insur')->default(0)->comment('1=yes,0=no')->change();
        });

        Schema::table('client_office_insurances', function (Blueprint $table) {
            $table->integer('provious_insurance_policy')->default(0)->comment('1=yes,0=no')->change();
            $table->integer('insurance_declined_issue_status')->default(0)->comment('1=yes,0=no')->change();
            $table->integer('claims_5_year_status')->default(0)->comment('1=yes,0=no')->change();
        });


    }

    public function down()
    {
        Schema::table('client_marine_insurances', function (Blueprint $table) {
            $table->enum('existing_policy_status', [1, 2])->default(2)->comment('1=yes,2=no')->change();
            $table->enum('declined_insurance_status', [1, 2])->default(2)->comment('1=yes,2=no')->change();
            $table->enum('claims_accident_status', [1, 2])->default(2)->comment('1=yes,2=no')->change();
        });

        Schema::table('client_pets_insurances', function (Blueprint $table) {
            $table->enum('pets_existing_condition_status', [1, 2])->default(2)->comment('1=yes,2=no')->change();
        });

        Schema::table('family_medical_insurance_members', function (Blueprint $table) {
            $table->enum('previous_operation', [1, 2])->default(2)->comment('1=yes,2=no')->change();
            $table->enum('pregnant_status', [1, 2])->default(2)->comment('1=yes,2=no')->change();
            $table->enum('dangerous_status', [1, 2])->default(2)->comment('1=yes,2=no')->change();
        });

        Schema::table('client_family_medical_insurances', function (Blueprint $table) {
            $table->enum('existing_policy_status', [1, 2])->default(2)->comment('1=yes,2=no')->change();
            $table->enum('previous_operation', [1, 2])->default(2)->comment('1=yes,2=no')->change();
            $table->enum('pregnant_status', [1, 2])->default(2)->comment('1=yes,2=no')->change();
            $table->enum('dangerous_status', [1, 2])->default(2)->comment('1=yes,2=no')->change();
        });

        Schema::table('critical_illness_insurances', function (Blueprint $table) {
            $table->enum('previous_operation', [1, 2])->default(2)->comment('1=yes,2=no')->change();
            $table->enum('previous_insurance_policy', [1, 2])->default(2)->comment('1=yes,2=no')->change();
        });


        Schema::table('client_life_insurances', function (Blueprint $table) {
            $table->enum('previous_operation', [1, 2])->default(2)->comment('1=yes,2=no')->change();
            $table->enum('company_declined_policy', [1, 2])->default(2)->comment('1=yes,2=no')->change();
            $table->enum('exiting_life_insur', [1, 2])->default(2)->comment('1=yes,2=no')->change();
        });

        Schema::table('client_office_insurances', function (Blueprint $table) {
            $table->enum('provious_insurance_policy', [1, 2])->default(2)->comment('1=yes,2=no')->change();
            $table->enum('insurance_declined_issue_status', [1, 2])->default(2)->comment('1=yes,2=no')->change();
            $table->enum('claims_5_year_status', [1, 2])->default(2)->comment('1=yes,2=no')->change();
        });
    }
};

