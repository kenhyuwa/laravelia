<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnIndexOnPermissionTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $permissions = config('laravelia.models.permissions');
        if (Schema::hasTable((new $permissions)->getTable()) && !Schema::hasColumn((new $permissions)->getTable(), 'index')) {
            Schema::table((new $permissions)->getTable(), function(Blueprint $table) {
                $table->string('index')->index()->nullable()->after('id');
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $permissions = config('laravelia.models.permissions');
        if (Schema::hasColumn((new $permissions)->getTable(), 'index')) {
            Schema::table((new $permissions)->getTable(), function(Blueprint $table) {
                $table->dropColumn('index');
            });
        }
    }
}
