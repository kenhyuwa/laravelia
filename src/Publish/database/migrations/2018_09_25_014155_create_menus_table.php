<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMenusTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable((new $this->app['config']['laravelia.models.menu'])->getTable())) {
            Schema::create((new $this->app['config']['laravelia.models.menu'])->getTable(), function (Blueprint $table) {
                $table->uuid('id')->primary();
                // $table->char('group', 2)->nullable(); 
                $table->uuid('parent')->nullable(); 
                $table->integer('queue')->nullable(); 
                $table->string('en_name')->nullable()->unique(); 
                $table->string('id_name')->nullable()->unique();
                $table->string('icon')->nullable()->default('fa fa-windows');
                $table->string('route')->nullable(); 
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('menu_role')) {
            Schema::create('menu_role', function (Blueprint $table) {
                $table->uuid('menu_id');
                $table->uuid('role_id');
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
        Schema::dropIfExists((new $this->app['config']['laravelia.models.menu'])->getTable());
        Schema::dropIfExists('menu_role');
    }
}
