<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRolePermissionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('role_permissions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('role_id')->nullable();
            $table->text('user')->nullable();
            $table->mediumText('contact')->nullable();
            $table->text('product')->nullable();
            $table->text('purchase')->nullable();
            $table->text('s_adjust')->nullable();
            $table->mediumText('expense')->nullable();
            $table->text('sale')->nullable();
            $table->text('register')->nullable();
            $table->text('report')->nullable();
            $table->text('setup')->nullable();
            $table->text('dashboard')->nullable();
            $table->text('accounting')->nullable();
            $table->text('hrms')->nullable();
            $table->text('essential')->nullable();
            $table->text('manufacturing')->nullable();
            $table->text('project')->nullable();
            $table->text('repair')->nullable();
            $table->text('superadmin')->nullable();
            $table->text('e_commerce')->nullable();
            $table->mediumText('others')->nullable();
            $table->boolean('is_super_admin_role')->default(0);
            $table->timestamps();
            
            $table->foreign('role_id', 'role_permissions_role_id_foreign')->references('id')->on('roles')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('role_permissions');
    }
}
