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
        Schema::create('customer_companies', function (Blueprint $table) {
            $table->id();
            $table->string('prefix_id')->nullable();
            $table->string('name', 50);
            $table->string('alias_name', 50);
            $table->string('street_name', 30);
            $table->string('street_number', 30);
            $table->string('zip_code', 10);
            $table->string('city', 30);
            $table->string('country_iso_code');
            $table->string('contact_person_first_name', 30);
            $table->string('contact_person_last_name', 30);
            $table->string('contact_person_email')->nullable();
            $table->string('contact_person_phone_iso_code')->nullable();
            $table->string('contact_person_phone')->nullable();
            $table->string('contact_person_full_phone_number')->nullable();
            $table->integer('auto_logout_time')->nullable();
            $table->boolean('device_authentication_required')->default(false);
            $table->boolean('hierarchy_elements_required')->default(false);
            $table->enum('status', ['active', 'inactive'])->default('active');

            $table->string('contact_person_name')->virtualAs("CONCAT(`contact_person_first_name`, ' ', `contact_person_last_name`)")->index();;
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
        Schema::dropIfExists('customer_companies');
    }
};
