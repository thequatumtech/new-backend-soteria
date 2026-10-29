<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('client_office_insurances', function (Blueprint $table) {
            $table->string('authorized_position')->nullable()->after('authorized_insurance_police_status');
            $table->text('previous_insurance_details')->nullable()->after('provious_insurance_policy');
            $table->text('insurance_declined_details')->nullable()->after('insurance_declined_issue_status');
            $table->text('claims_5_year_details')->nullable()->after('claims_5_year_status');
        });
    }

    public function down(): void
    {
        Schema::table('client_office_insurances', function (Blueprint $table) {
            $table->dropColumn([
                'authorized_position',
                'previous_insurance_details',
                'insurance_declined_details',
                'claims_5_year_details',
            ]);
        });
    }
};
