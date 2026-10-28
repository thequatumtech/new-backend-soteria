<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    // Yes/No fields were enum('1','2') with 2=no. The app sends 1=yes, 0=no,
    // which the enum rejects, so convert them to 0/1.
    private $columns = [
        'american_notionality_status',
        'employee_status',
        'previous_operation',
        'company_declined_policy',
        'exiting_life_insur',
    ];

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        foreach ($this->columns as $column) {
            DB::statement("ALTER TABLE `client_life_insurances` MODIFY `{$column}` VARCHAR(10) NULL");
            DB::statement("UPDATE `client_life_insurances` SET `{$column}` = '0' WHERE `{$column}` = '2'");
            // Old enum rows can hold '' (invalid value), which TINYINT rejects
            DB::statement("UPDATE `client_life_insurances` SET `{$column}` = NULL WHERE `{$column}` NOT IN ('0', '1')");
            DB::statement("ALTER TABLE `client_life_insurances` MODIFY `{$column}` TINYINT(1) NULL DEFAULT 0 COMMENT '1=yes,0=no'");
        }
    }

    public function down()
    {
        foreach ($this->columns as $column) {
            DB::statement("ALTER TABLE `client_life_insurances` MODIFY `{$column}` VARCHAR(10) NULL");
            DB::statement("UPDATE `client_life_insurances` SET `{$column}` = '2' WHERE `{$column}` = '0'");
            DB::statement("ALTER TABLE `client_life_insurances` MODIFY `{$column}` ENUM('1','2') NOT NULL DEFAULT '2' COMMENT '1=yes,2=no'");
        }
    }
};
