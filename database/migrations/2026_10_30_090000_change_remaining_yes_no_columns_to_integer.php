<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    // Yes/No fields still on enum('1','2') with 2=no. The app sends 1=yes, 0=no, which
    // the enum rejects ("Data truncated"), so convert them to 0/1 like the other products.
    private $columns = [
        'client_office_insurances' => [
            'partner_company_status',
            'authorized_insurance_police_status',
            'auth_company_register_status',
        ],
        'client_marine_insurances' => [
            'company_partner_status',
            'company_authorized_status',
            'company_register_status',
        ],
    ];

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        foreach ($this->columns as $table => $columns) {
            foreach ($columns as $column) {
                DB::statement("ALTER TABLE `{$table}` MODIFY `{$column}` VARCHAR(10) NULL");
                DB::statement("UPDATE `{$table}` SET `{$column}` = '0' WHERE `{$column}` = '2'");
                // Old enum rows can hold '' (invalid value), which TINYINT rejects
                DB::statement("UPDATE `{$table}` SET `{$column}` = NULL WHERE `{$column}` NOT IN ('0', '1')");
                DB::statement("ALTER TABLE `{$table}` MODIFY `{$column}` TINYINT(1) NULL DEFAULT 0 COMMENT '1=yes,0=no'");
            }
        }
    }

    public function down()
    {
        foreach ($this->columns as $table => $columns) {
            foreach ($columns as $column) {
                DB::statement("ALTER TABLE `{$table}` MODIFY `{$column}` VARCHAR(10) NULL");
                DB::statement("UPDATE `{$table}` SET `{$column}` = '2' WHERE `{$column}` = '0' OR `{$column}` IS NULL");
                DB::statement("ALTER TABLE `{$table}` MODIFY `{$column}` ENUM('1','2') NOT NULL DEFAULT '2' COMMENT '1=yes,2=no'");
            }
        }
    }
};
