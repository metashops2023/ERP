<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAddonsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('addons', function (Blueprint $table) {
            $table->id();
            $table->boolean('branches')->default(0);
            $table->boolean('hrm')->default(0);
            $table->boolean('todo')->default(0);
            $table->boolean('service')->default(0);
            $table->boolean('manufacturing')->default(0);
            $table->boolean('e_commerce')->default(0);
            $table->bigInteger('branch_limit')->default(0);
            $table->bigInteger('cash_counter_limit')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('addons');
    }
}
