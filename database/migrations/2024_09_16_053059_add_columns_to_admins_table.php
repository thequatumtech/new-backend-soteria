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
        Schema::table('admins', function (Blueprint $table) {
            $table->dropColumn(['name','national_id']);
            $table->string('first_name',100)->nullable()->after('id');
            $table->string('second_name',100)->nullable()->after('first_name');
            $table->string('third_name',100)->nullable()->after('second_name');
            $table->string('last_name',100)->nullable()->after('third_name');
            $table->enum('language',['en','ar'])->default('en')->comment('en=english,ar=arabic')->after('last_name');
            $table->bigInteger('nationality_id')->after('language');
            $table->string('national_id_no',100)->nullable()->after('nationality_id');
            $table->string('residence_id_no',100)->nullable()->after('national_id_no');
            $table->date('birth_date')->nullable()->after('residence_id_no');
            $table->bigInteger('mobile_no')->nullable()->after('birth_date')->change();
            $table->bigInteger('country_id')->nullable()->after('mobile_no');
            $table->bigInteger('residing_country_id')->nullable()->after('country_id');
            $table->bigInteger('city_id')->nullable()->after('residing_country_id');
            $table->bigInteger('district_id')->nullable()->after('city_id');
            $table->string('street_name',100)->nullable()->after('district_id');
            $table->string('building_no',100)->nullable()->after('street_name');
            $table->string('company_name',100)->nullable()->after('building_no');
            $table->bigInteger('occupation_id')->nullable()->after('company_name');
            $table->string('work_nature',100)->nullable()->after('occupation_id');
            $table->bigInteger('company_city_id')->nullable()->after('work_nature');
            $table->bigInteger('company_district_id')->nullable()->after('company_city_id');
            $table->string('company_street_name',100)->nullable()->after('company_district_id');
            $table->string('company_building_no',100)->nullable()->after('company_street_name');
            $table->string('company_contact_no',100)->nullable()->after('company_building_no');
            $table->string('id_front',100)->nullable()->after('company_contact_no');
            $table->string('id_back',100)->nullable()->after('id_front');
            $table->string('profile_pic',100)->nullable()->after('id_back');
            $table->bigInteger('admin_id')->nullable()->after('profile_pic');
            $table->enum('is_super_admin',['0','1'])->default('0')->after('is_super_admin')->after('admin_id');
            $table->longText('authorized_routes')->nullable()->after('is_super_admin');
            $table->timestamp('deleted_at')->nullable()->after('updated_at');
            $table->string('gender',255)->default(1)->nullable()->comment('1=MALE,2=FEMALE')->after('birth_date')->change();
        });
        \Illuminate\Support\Facades\DB::statement("ALTER TABLE `admins` CHANGE `created_at` `created_at` TIMESTAMP NULL DEFAULT NULL AFTER `authorized_routes`;");
        \Illuminate\Support\Facades\DB::statement("ALTER TABLE `admins` CHANGE `updated_at` `updated_at` TIMESTAMP NULL DEFAULT NULL AFTER `created_at`;");
        \Illuminate\Support\Facades\DB::statement("ALTER TABLE `admins` CHANGE `deleted_at` `deleted_at` TIMESTAMP NULL DEFAULT NULL AFTER `updated_at`;");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('admins', function (Blueprint $table) {
            $table->dropColumn([
                'first_name',
                'second_name',
                'third_name',
                'last_name',
                'language',
                'nationality_id',
                'national_id_no',
                'residence_id_no',
                'birth_date',
                'country_id',
                'residing_country_id',
                'city_id',
                'district_id',
                'street_name',
                'building_no',
                'company_name',
                'occupation_id',
                'work_nature',
                'company_city_id',
                'company_district_id',
                'company_street_name',
                'company_building_no',
                'company_contact_no',
                'id_front',
                'id_back',
                'profile_pic',
                'admin_id',
                'deleted_at',
                'is_super_admin',
                'authorized_routes'
            ]);
            $table->string('name')->after('id');
            $table->bigInteger('mobile_no')->nullable()->after('updated_at')->change();
            $table->bigInteger('national_id')->nullable()->after('gender');
            $table->string('gender',255)->nullable()->after('mobile_no')->change();
        });
        \Illuminate\Support\Facades\DB::statement("ALTER TABLE `admins` CHANGE `national_id` `national_id` bigint(20) NULL DEFAULT NULL AFTER `gender`;");
        \Illuminate\Support\Facades\DB::statement("ALTER TABLE `admins` CHANGE `created_at` `created_at` TIMESTAMP NULL DEFAULT NULL AFTER `password`;");
        \Illuminate\Support\Facades\DB::statement("ALTER TABLE `admins` CHANGE `updated_at` `updated_at` TIMESTAMP NULL DEFAULT NULL AFTER `created_at`;");
    }
};
