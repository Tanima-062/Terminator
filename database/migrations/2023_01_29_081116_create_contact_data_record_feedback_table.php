<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
// use Illuminate\Support\Facades\Schema;
use Brokenice\LaravelMysqlPartition\Schema\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contact_data_record_feedback', function (Blueprint $table) {
            // $table->id();
            $table->unsignedBigInteger('id');
            // $table->foreignId('contact_data_record_id')->constrained('contact_data_records', 'id');
            $table->unsignedBigInteger('contact_data_record_id');
            $table->enum('feedback', ['Not Reached', 'Wrong Number', 'No Interest', 'Sick', 'Already terminated', 'Other Offer received', 'Call later', 'Appointment', 'No Potential']);
            $table->text('feedback_remarks')->nullable();
            $table->date('call_date')->nullable();
            $table->time('call_time')->nullable();
            $table->timestamps();

            $table->primary(['id',  'contact_data_record_id']);

        });

        Schema::forceAutoIncrement('contact_data_record_feedback', 'id', 'bigint');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('contact_data_record_feedback');
    }
};
