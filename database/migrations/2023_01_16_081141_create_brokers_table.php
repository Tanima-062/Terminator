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
        Schema::create('brokers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_company_id')->constrained('customer_companies');
            $table->string('prefix_id')->nullable();
            $table->string('name', 50);
            $table->string('street_name', 30)->nullable();
            $table->string('street_number', 30)->nullable();
            $table->string('zip_code', 10)->nullable();
            $table->string('city', 30)->nullable();
            $table->string('country_iso_code')->nullable();
            $table->string('contact_person_first_name', 30);
            $table->string('contact_person_last_name', 30);
            $table->string('contact_person_full_name', 70)->nullable();
            $table->string('contact_person_email')->nullable();
            $table->string('contact_person_phone_iso_code')->nullable();
            $table->string('contact_person_phone')->nullable();
            $table->string('contact_person_full_phone_number')->nullable();
            $table->enum('status', ['active', 'inactive'])->default('active');
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
        Schema::dropIfExists('brokers');
    }
};
