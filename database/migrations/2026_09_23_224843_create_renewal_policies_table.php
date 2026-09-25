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
        Schema::create('renewal_policies', function (Blueprint $table) {
            $table->id();

              $table->foreignId('old_policy_id')
                ->constrained('purchase_policy')
                ->cascadeOnDelete();

            $table->foreignId('new_policy_id')
                ->constrained('purchase_policy')
                ->cascadeOnDelete();
                
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('renewal_policies');
    }
};
