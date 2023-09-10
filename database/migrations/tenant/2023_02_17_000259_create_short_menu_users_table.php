<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateShortMenuUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('short_menu_users', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('short_menu_id')->nullable();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->boolean('is_delete_in_update')->default(0);
            $table->timestamps();
            
            $table->foreign('short_menu_id', 'short_menu_users_short_menu_id_foreign')->references('id')->on('short_menus')->onDelete('cascade');
            $table->foreign('user_id', 'short_menu_users_user_id_foreign')->references('id')->on('admin_and_users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('short_menu_users');
    }
}
