<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGeneralSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('general_settings', function (Blueprint $table) {
            $table->id();
            $table->longText('business')->nullable();
            $table->longText('tax')->nullable();
            $table->longText('product')->nullable();
            $table->longText('sale')->nullable();
            $table->longText('pos')->nullable();
            $table->longText('purchase')->nullable();
            $table->longText('dashboard')->nullable();
            $table->longText('system')->nullable();
            $table->longText('prefix')->nullable();
            $table->string('send_es_settings')->nullable();
            $table->longText('email_setting')->nullable();
            $table->longText('sms_setting')->nullable();
            $table->longText('modules')->nullable();
            $table->longText('reward_poing_settings')->nullable();
            $table->text('mf_settings')->nullable()->comment("manufacturing_settings");
            $table->boolean('multi_branches')->default(0)->comment("is_activated");
            $table->boolean('hrm')->default(0)->comment("is_activated");
            $table->boolean('services')->default(0)->comment("is_activated");
            $table->boolean('manufacturing')->default(0)->comment("is_activated");
            $table->boolean('projects')->default(0)->comment("is_activated");
            $table->boolean('essentials')->default(0)->comment("is_activated");
            $table->boolean('e_commerce')->default(0)->comment("is_activated");
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
        Schema::dropIfExists('general_settings');
    }
}
