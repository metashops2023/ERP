<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSvJobSheetsPartsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sv_job_sheets_parts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('job_sheet_id');
            $table->unsignedBigInteger('product_id')->nullable();
            $table->unsignedBigInteger('product_variant_id')->nullable();
            $table->timestamps();
            
            $table->foreign('job_sheet_id', 'sv_job_sheets_parts_job_sheet_id_foreign')->references('id')->on('sv_job_sheets')->onDelete('cascade');
            $table->foreign('product_id', 'sv_job_sheets_parts_product_id_foreign')->references('id')->on('products')->onDelete('cascade');
            $table->foreign('product_variant_id', 'sv_job_sheets_parts_product_variant_id_foreign')->references('id')->on('product_variants')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sv_job_sheets_parts');
    }
}
