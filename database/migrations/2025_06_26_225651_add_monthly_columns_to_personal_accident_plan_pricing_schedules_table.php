<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMonthlyColumnsToPersonalAccidentPlanPricingSchedulesTable extends Migration
{
    public function up()
    {
        Schema::table('personal_accident_plan_pricing_schedules', function (Blueprint $table) {
            // Add m_1 to m_12, skip if already exists (like m_3, m_6, etc.)
            for ($i = 1; $i <= 12; $i++) {
                $column = 'm_' . $i;
                if (!Schema::hasColumn('personal_accident_plan_pricing_schedules', $column)) {
                    $table->decimal($column, 10, 2)->nullable()->after('age'); // or adjust position as needed
                }
            }
        });
    }

    public function down()
    {
        Schema::table('personal_accident_plan_pricing_schedules', function (Blueprint $table) {
            for ($i = 1; $i <= 12; $i++) {
                $column = 'm_' . $i;
                if (Schema::hasColumn('personal_accident_plan_pricing_schedules', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
}
