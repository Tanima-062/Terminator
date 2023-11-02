<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('workflow_settings', function (Blueprint $table) {
            $table->id();
            $table->integer('call_attempt_limit')->nullable();
            $table->integer('contact_limit')->nullable();
            $table->float('contact_record_creation_cost')->nullable();
            $table->float('contact_record_creation_revenue')->nullable();
            $table->float('data_verification_cost')->nullable();
            $table->float('data_verification_revenue')->nullable();
            $table->float('lead_quality_check_cost')->nullable();
            $table->float('lead_quality_check_revenue')->nullable();
            $table->float('edit_lead_quality_topics_cost')->nullable();
            $table->float('edit_lead_quality_topics_revenue')->nullable();
            $table->float('appointment_contact_cost')->nullable();
            $table->float('appointment_contact_revenue')->nullable();
            $table->float('appointment_quality_check_cost')->nullable();
            $table->float('appointment_quality_check_revenue')->nullable();
            $table->float('edit_appointment_quality_topics_cost')->nullable();
            $table->float('edit_appointment_quality_topics_revenue')->nullable();
            $table->float('carry_out_appointment_reminder_cost')->nullable();
            $table->float('carry_out_appointment_reminder_revenue')->nullable();
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
        Schema::dropIfExists('workflow_settings');
    }
};
