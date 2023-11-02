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
        Schema::create('contact_data_record_availabilities', function (Blueprint $table) {
            // $table->id();
            $table->unsignedBigInteger('id');
            // $table->foreignId('contact_data_record_id')->constrained('contact_data_records', 'id');
            // $table->unsignedBigInteger('contact_data_record_id');
            $table->unsignedBigInteger('contact_data_record_id');
            $table->enum('day', ['Monday','Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday']);
            $table->time('first_start_time')->nullable();
            $table->time('first_end_time')->nullable();
            $table->time('last_start_time')->nullable();
            $table->time('last_end_time')->nullable();
            $table->timestamps();

            // $table->foreign('contact_data_record_id', 'contact_data_record_id_foreign')->references('id')->on('contact_data_records');
            $table->primary(['id',  'contact_data_record_id']);

        });

        Schema::forceAutoIncrement('contact_data_record_availabilities', 'id', 'bigint');

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('contact_data_record_availabilities');
    }
};
