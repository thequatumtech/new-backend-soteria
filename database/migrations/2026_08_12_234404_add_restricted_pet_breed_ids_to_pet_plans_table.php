<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pet_plans', function (Blueprint $table) {
            $table->longText('restricted_pet_breed_ids')->nullable()->after('restricted_pet_age_ids');
        });
    }

    public function down(): void
    {
        Schema::table('pet_plans', function (Blueprint $table) {
            $table->dropColumn('restricted_pet_breed_ids');
        });
    }
};
