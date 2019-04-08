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
        if (Schema::hasTable((new $this->app['config']['laravelia.models.permissions'])->getTable()) && !Schema::hasColumn((new $this->app['config']['laravelia.models.permissions'])->getTable(), 'index')) {
            Schema::table((new $this->app['config']['laravelia.models.permissions'])->getTable(), function(Blueprint $table) {
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
        if (Schema::hasColumn((new $this->app['config']['laravelia.models.permissions'])->getTable(), 'index')) {
            Schema::table((new $this->app['config']['laravelia.models.permissions'])->getTable(), function(Blueprint $table) {
                $table->dropColumn('index');
            });
        }
    }
}
