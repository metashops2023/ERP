<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSvJobSheetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sv_job_sheets', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_id')->nullable();
            $table->unsignedBigInteger('customer_id')->nullable();
            $table->unsignedBigInteger('branch_id')->nullable();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->unsignedBigInteger('brand_id')->nullable();
            $table->unsignedBigInteger('device_id')->nullable();
            $table->unsignedBigInteger('model_id')->nullable();
            $table->unsignedBigInteger('status_id')->nullable();
            $table->boolean('is_completed')->default(0);
            $table->tinyInteger('service_type')->nullable();
            $table->mediumText('address')->nullable();
            $table->decimal('cost', 22, 2)->default(0.00);
            $table->text('checklist')->nullable();
            $table->string('serial_number')->nullable();
            $table->string('password')->nullable();
            $table->text('configuration')->nullable()->comment("Product Configuration");
            $table->text('Condition')->nullable()->comment("Condition Of The Product");
            $table->text('customer_report')->nullable()->comment("Problem Reported By The Customer");
            $table->text('technician_comment')->nullable();
            $table->string('delivery_date')->nullable();
            $table->string('send_notification')->nullable();
            $table->string('date')->nullable();
            $table->string('time')->nullable();
            $table->string('month')->nullable();
            $table->string('year')->nullable();
            $table->timestamps();
            
            $table->foreign('branch_id', 'sv_job_sheets_branch_id_foreign')->references('id')->on('branches')->onDelete('cascade');
            $table->foreign('brand_id', 'sv_job_sheets_brand_id_foreign')->references('id')->on('brands')->onDelete('cascade');
            $table->foreign('customer_id', 'sv_job_sheets_customer_id_foreign')->references('id')->on('customers')->onDelete('cascade');
            $table->foreign('device_id', 'sv_job_sheets_device_id_foreign')->references('id')->on('sv_devices')->onDelete('cascade');
            $table->foreign('model_id', 'sv_job_sheets_model_id_foreign')->references('id')->on('sv_device_models')->onDelete('cascade');
            $table->foreign('status_id', 'sv_job_sheets_status_id_foreign')->references('id')->on('sv_status')->onDelete('set NULL');
            $table->foreign('user_id', 'sv_job_sheets_user_id_foreign')->references('id')->on('admin_and_users')->onDelete('set NULL');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sv_job_sheets');
    }
}
