<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class LaraveliaSetupTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return  void
     */
    public function up()
    {
        // Create table for storing roles
        $roles = config('laravelia.models.roles');
        $permissions = config('laravelia.models.permissions');
        $users = config('laravelia.models.users');
        if (!Schema::hasTable((new $roles)->getTable())) {
            Schema::create((new $roles)->getTable(), function (Blueprint $table) {
                $table->uuid('id')->primary();
                $table->string('name')->unique();
                $table->string('display_name')->nullable();
                $table->string('description')->nullable();
                $table->timestamps();
            });
        }

        // Create table for storing permissions
        if (!Schema::hasTable((new $permissions)->getTable())) {
            Schema::create((new $permissions)->getTable(), function (Blueprint $table) {
                $table->uuid('id')->primary();
                $table->string('name')->unique();
                $table->string('display_name')->nullable();
                $table->string('description')->nullable();
                $table->timestamps();
            });
        }

        // Create table for associating roles to users and teams (Many To Many Polymorphic)
        if (!Schema::hasTable('role_user')) {
            Schema::create('role_user', function (Blueprint $table) use ($roles, $users) {
                $table->uuid('role_id');
                $table->uuid('user_id');
                $table->string('user_type');

                // $table->primary(['user_id', 'role_id', 'user_type']);

                $table->foreign('role_id')->references('id')->on(
                    (new $roles)->getTable()
                )
                    ->onUpdate('cascade')->onDelete('cascade');
                $table->foreign('user_id')->references('id')->on(
                    (new $users)->getTable()
                )
                    ->onUpdate('cascade')->onDelete('cascade');
            });
        }

        // Create table for associating permissions to users (Many To Many Polymorphic)
        if (!Schema::hasTable('permission_user')) {
            Schema::create('permission_user', function (Blueprint $table) use($permissions, $users) {
                $table->uuid('permission_id');
                $table->uuid('user_id');
                $table->string('user_type');

                // $table->primary(['user_id', 'permission_id', 'user_type']);

                $table->foreign('permission_id')->references('id')->on(
                    (new $permissions)->getTable()
                )
                    ->onUpdate('cascade')->onDelete('cascade');
                $table->foreign('user_id')->references('id')->on(
                    (new $users)->getTable()
                )
                    ->onUpdate('cascade')->onDelete('cascade');
            });
        }

        // Create table for associating permissions to roles (Many-to-Many)
        if (!Schema::hasTable('permission_role')) {
            Schema::create('permission_role', function (Blueprint $table) use($permissions, $roles) {
                $table->uuid('permission_id');
                $table->uuid('role_id');

                // $table->primary(['permission_id', 'role_id']);

                $table->foreign('permission_id')->references('id')->on(
                    (new $permissions)->getTable()
                )
                    ->onUpdate('cascade')->onDelete('cascade');
                $table->foreign('role_id')->references('id')->on(
                    (new $roles)->getTable()
                )
                    ->onUpdate('cascade')->onDelete('cascade');
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return  void
     */
    public function down()
    {
        $roles = config('laravelia.models.roles');
        $permissions = config('laravelia.models.permissions');

        Schema::dropIfExists('permission_user');
        Schema::dropIfExists('permission_role');
        Schema::dropIfExists(
            (new $permissions)->getTable()
        );
        Schema::dropIfExists('role_user');
        Schema::dropIfExists(
            (new $roles)->getTable()
        );
    }
}
