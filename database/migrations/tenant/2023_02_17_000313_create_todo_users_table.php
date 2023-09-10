<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTodoUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('todo_users', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('todo_id');
            $table->unsignedBigInteger('user_id');
            $table->boolean('is_delete_in_update')->default(0);
            $table->timestamps();
            
            $table->foreign('todo_id', 'todo_users_todo_id_foreign')->references('id')->on('todos')->onDelete('cascade');
            $table->foreign('user_id', 'todo_users_user_id_foreign')->references('id')->on('admin_and_users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('todo_users');
    }
}
