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
        Schema::create('broker_users', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users', 'id');
            $table->foreignId('broker_id')->constrained('brokers');
            $table->foreignId('customer_company_id')->constrained('customer_companies');
            $table->string('prefix_id')->nullable();
            $table->string('correspondence_language');
            // $table->foreignId('role_id')->constrained('roles');
            // $table->foreignId('language_id')->constrained('languages');
            $table->enum('salutation', ['Ms', 'Mr', '/'])->default('/');
            // $table->string('first_name', 30);
            // $table->string('last_name', 30);
            // $table->string('full_name', 70)->nullable();
            // $table->string('email')->nullable();
            $table->string('phone_iso_code')->nullable();
            $table->string('phone')->nullable();
            $table->string('full_phone_number')->nullable();
            $table->enum('role', ['Admin', 'Intermediary']);
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
        Schema::dropIfExists('broker_users');
    }
};
