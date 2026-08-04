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
        Schema::create('life_plan_policy_covers', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('life_plan_id');
            $table->string('cover_name');
            $table->float('cover_limit')->default(0);
            $table->enum('cover_limit_type',[1,2])->default(2)->comment('1=amount,2=percentage');
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
        Schema::dropIfExists('life_plan_policy_covers');
    }
};
