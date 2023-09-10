<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSvDeviceModelsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sv_device_models', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('brand_id')->nullable();
            $table->unsignedBigInteger('device_id')->nullable();
            $table->string('model_name')->nullable();
            $table->text('checklist')->nullable();
            $table->timestamps();
            
            $table->foreign('brand_id', 'sv_device_models_brand_id_foreign')->references('id')->on('brands')->onDelete('cascade');
            $table->foreign('device_id', 'sv_device_models_device_id_foreign')->references('id')->on('sv_devices')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sv_device_models');
    }
}
