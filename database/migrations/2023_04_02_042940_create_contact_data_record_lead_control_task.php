<?php

use App\Models\ContactDataRecord;
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
        Schema::create('contact_data_record_lead_control_tasks', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(ContactDataRecord::class);
            $table->string('lead_control_task', 255)->nullable();
            $table->string('lead_control_task_remarks', 255)->nullable();
            $table->boolean('lead_control_task_status')->default(false);
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
        Schema::dropIfExists('contact_data_record_lead_control_task');
    }
};
