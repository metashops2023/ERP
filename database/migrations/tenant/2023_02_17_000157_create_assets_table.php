<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAssetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('assets', function (Blueprint $table) {
            $table->id();
            $table->string('asset_name');
            $table->unsignedBigInteger('type_id')->nullable();
            $table->unsignedBigInteger('branch_id')->nullable();
            $table->decimal('quantity', 22, 2)->default(0.00);
            $table->decimal('per_unit_value', 22, 2)->default(0.00);
            $table->decimal('total_value', 22, 2)->default(0.00);
            $table->timestamps();
            
            $table->foreign('branch_id', 'assets_branch_id_foreign')->references('id')->on('branches')->onDelete('cascade');
            $table->foreign('type_id', 'assets_type_id_foreign')->references('id')->on('asset_types')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('assets');
    }
}
