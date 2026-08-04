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
        Schema::create('discount_coupons', function (Blueprint $table) {
            $table->id();
            $table->string('coupon_code');
            $table->bigInteger('insurance_company_id');
            $table->bigInteger('line_of_business_id');
            $table->float('percentage')->default(0);
            $table->date('effective_date');
            $table->date('expiry_date');
            $table->string('client_ids')->nullable();
            $table->longText('description')->nullable();
            $table->string('attachment')->nullable();
            $table->enum('send_to', ['1', '2'])->default('1')->comment('1: Email, 2: Mobile No')->nullable();
            $table->enum('is_sent', ['0', '1'])->default('0')->comment('0: No, 1: Yes');
            $table->enum('status', ['1', '0'])->default('1')->comment('1: Active, 0: Inactive');
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
        Schema::dropIfExists('discount_coupons');
    }
};
