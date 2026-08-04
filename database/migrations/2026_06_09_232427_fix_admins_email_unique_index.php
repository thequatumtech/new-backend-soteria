<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class FixAdminsEmailUniqueIndex extends Migration
{
    public function up()
    {
        Schema::table('admins', function (Blueprint $table) {
            // Drop the plain unique index that ignores soft deletes
            $table->dropUnique('admins_email_unique');

            // No DB-level unique index needed — Laravel validation handles it
            // with whereNull('deleted_at') filter
        });
    }

    public function down()
    {
        Schema::table('admins', function (Blueprint $table) {
            $table->unique('email', 'admins_email_unique');
        });
    }
}
