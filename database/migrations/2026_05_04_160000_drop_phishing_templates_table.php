<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::dropIfExists('phishing_templates');

        // Remove the orphan permission row created by the old RoleAndPermissionSeeder.
        DB::table('permissions')->where('name', 'phishing simulation')->delete();
    }

    public function down()
    {
        // Intentionally not recreating the table or the permission. The phishing
        // simulation feature was removed; reinstate it via a forward migration
        // if it ever comes back.
    }
};
