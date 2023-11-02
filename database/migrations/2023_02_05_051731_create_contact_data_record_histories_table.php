<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

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
        Schema::create('contact_data_record_histories', function (Blueprint $table) {
            // $table->id();
            $table->unsignedBigInteger('id');
            // $table->foreignId('user_id')->constrained('users', 'id');
            $table->unsignedBigInteger('user_id');
            // $table->foreignId('contact_data_record_id')->constrained('contact_data_records', 'id');
            $table->unsignedBigInteger('contact_data_record_id');
            $table->string('action')->nullable()->index();
            $table->boolean('status_change')->default(false);
            $table->string('old_status')->nullable()->index();
            $table->string('new_status')->nullable()->index();

            $table->boolean('category_change')->default(false);
            $table->string('old_category')->nullable();
            $table->string('new_category')->nullable();
            $table->string('user_type')->nullable();
            $table->string('notes')->nullable();
            $table->timestamps();
            // $table->dropPrimary('id');
            $table->primary(['id',  'contact_data_record_id']);
        });

        Schema::forceAutoIncrement('contact_data_record_histories', 'id', 'bigint');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('contact_data_record_histories');
    }
};
