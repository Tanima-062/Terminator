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
        Schema::create('contact_data_record_appointments', function (Blueprint $table) {
            $table->id();
            $table->string('prefix_id')->nullable();
            // $table->foreignId('customer_company_id')->constrained('customer_companies', 'id');
            $table->unsignedBigInteger('customer_company_id');
            // $table->foreignId('contact_data_record_id')->constrained('contact_data_records', 'id');
            $table->unsignedBigInteger('contact_data_record_id');
            // $table->foreignId('user_id')->nullable()->constrained('users', 'id');
            $table->unsignedBigInteger('user_id');
            $table->date('appointment_date');
            $table->time('appointment_time');
            $table->text('notes')->nullable();
            $table->string('control_status_appointment')->nullable();
            $table->string('appointment_control_task', 50)->nullable();
            $table->string('appointment_control_task_remarks', 250)->nullable();
            $table->boolean('appointment_control_task_status')->default(false);
            $table->enum('appointment_reminder_status', ['Done', 'Not reached - Appointment reminder', 'Cancelled'])->nullable();
            $table->string('appointment_reminder_remarks', 250)->nullable();
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
        Schema::dropIfExists('contact_data_record_appointments');
    }
};
