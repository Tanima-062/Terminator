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
        Schema::create('contact_data_record_allocates', function (Blueprint $table) {
            // $table->id();
            $table->unsignedBigInteger('id');
            $table->string('prefix_id')->nullable();
            // $table->foreignId('allocate_by_user_id')->constrained('users', 'id');
            $table->unsignedBigInteger('allocate_by_user_id');

            // $table->foreignId('customer_company_id')->constrained('customer_companies', 'id');
            $table->unsignedBigInteger('customer_company_id');
            // $table->foreignId('contact_data_record_id')->constrained('contact_data_records', 'id');
            $table->unsignedBigInteger('contact_data_record_id');

            // $table->foreignId('user_id')->nullable()->constrained('users', 'id');
            $table->unsignedBigInteger('user_id')->nullable();
            // $table->foreignId('broker_user_id')->nullable()->constrained('broker_users', 'id');
            $table->unsignedBigInteger('broker_user_id')->nullable();
            // $table->foreignId('internal_user_id')->nullable()->constrained('internal_users', 'id');
            $table->unsignedBigInteger('internal_user_id')->nullable();
            // $table->foreignId('organization_element_id')->nullable()->constrained('organization_elements', 'id');
            $table->unsignedBigInteger('organization_element_id')->nullable();

            // $table->foreignId('broker_id')->nullable()->constrained('brokers', 'id');
            $table->unsignedBigInteger('broker_id')->nullable();
            // $table->foreignId('campaign_id')->nullable()->constrained('campaigns', 'id');
            $table->unsignedBigInteger('campaign_id')->nullable();
            $table->enum('type', ['Leader Head of', 'Manager', 'Quality controller', 'Call agent', 'Broker', 'Broker user', 'variableA']);
            $table->timestamps();

            $table->primary(['id',  'contact_data_record_id']);
        });

        Schema::forceAutoIncrement('contact_data_record_allocates', 'id', 'bigint');

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('contact_data_record_allocates');
    }
};
