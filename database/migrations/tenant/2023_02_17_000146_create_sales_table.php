<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSalesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sales', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_id', 100)->nullable();
            $table->unsignedBigInteger('branch_id')->nullable();
            $table->unsignedBigInteger('customer_id')->nullable();
            $table->unsignedBigInteger('sale_account_id')->nullable();
            $table->tinyInteger('pay_term')->nullable();
            $table->bigInteger('pay_term_number')->nullable();
            $table->bigInteger('total_item');
            $table->decimal('net_total_amount', 22, 2)->default(0.00);
            $table->boolean('order_discount_type')->default(1);
            $table->decimal('order_discount', 22, 2)->default(0.00);
            $table->decimal('order_discount_amount', 22, 2)->default(0.00);
            $table->decimal('redeem_point', 22, 2)->default(0.00);
            $table->decimal('redeem_point_rate', 22, 2)->default(0.00);
            $table->string('shipment_details')->nullable();
            $table->mediumText('shipment_address')->nullable();
            $table->decimal('shipment_charge', 22, 2)->default(0.00);
            $table->tinyInteger('shipment_status')->nullable();
            $table->mediumText('delivered_to')->nullable();
            $table->mediumText('sale_note')->nullable();
            $table->decimal('order_tax_percent', 22, 2)->default(0.00);
            $table->decimal('order_tax_amount', 22, 2)->default(0.00);
            $table->decimal('total_payable_amount', 22, 2)->default(0.00);
            $table->decimal('paid', 22, 2)->default(0.00);
            $table->decimal('change_amount', 22, 2)->default(0.00);
            $table->decimal('due', 22, 2)->default(0.00);
            $table->boolean('is_return_available')->default(0);
            $table->boolean('ex_status')->default(0)->comment("0=exchangeed,1=exchanged");
            $table->decimal('sale_return_amount', 22, 2)->default(0.00);
            $table->decimal('sale_return_due', 22, 2)->default(0.00);
            $table->mediumText('payment_note')->nullable();
            $table->unsignedBigInteger('admin_id')->nullable();
            $table->boolean('status')->default(1)->comment("1=final;2=draft;3=challan;4=quatation;5=hold;6=suspended");
            $table->boolean('is_fixed_challen')->default(0);
            $table->string('date', 191)->nullable();
            $table->string('time', 191)->nullable();
            $table->timestamp('report_date')->nullable();
            $table->string('month', 191)->nullable();
            $table->string('year', 191)->nullable();
            $table->string('attachment', 191)->nullable();
            $table->decimal('gross_pay', 22, 2)->default(0.00);
            $table->decimal('previous_due', 22, 2)->default(0.00);
            $table->decimal('all_total_payable', 22, 2)->default(0.00);
            $table->decimal('previous_due_paid', 22, 2)->default(0.00);
            $table->decimal('customer_running_balance', 22, 2)->default(0.00);
            $table->boolean('created_by')->default(1)->comment("1=add_sale;2=pos");
            $table->timestamps();
            
            $table->foreign('branch_id', 'sales_branch_id_foreign')->references('id')->on('branches')->onDelete('cascade');
            $table->foreign('customer_id', 'sales_customer_id_foreign')->references('id')->on('customers')->onDelete('cascade');
            $table->foreign('sale_account_id', 'sales_sale_account_id_foreign')->references('id')->on('accounts')->onDelete('set NULL');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sales');
    }
}
