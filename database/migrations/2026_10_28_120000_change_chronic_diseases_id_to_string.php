<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    // The app sends multiple chronic diseases as comma-separated ids ("5,6"),
    // which a BIGINT column can't hold.
    private $tables = [
        'client_life_insurances',
        'critical_illness_insurances',
        'client_family_medical_insurances',
        'family_medical_insurance_members',
    ];

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        foreach ($this->tables as $table) {
            DB::statement("ALTER TABLE `{$table}` MODIFY `chronic_diseases_id` VARCHAR(255) NULL");
        }
    }

    public function down()
    {
        foreach ($this->tables as $table) {
            DB::statement("ALTER TABLE `{$table}` MODIFY `chronic_diseases_id` BIGINT NULL");
        }
    }
};
