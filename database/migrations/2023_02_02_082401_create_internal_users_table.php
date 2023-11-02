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
        Schema::create('internal_users', function (Blueprint $table) {
            $table->id();
            $table->string('prefix_id')->nullable();
            $table->foreignId('user_id')->constrained('users', 'id');
            $table->foreignId('roles_id')->constrained('company_roles', 'id');
            $table->foreignId('customer_company_id')->constrained('customer_companies', 'id');
            $table->string('phone_number')->nullable();
            $table->string('phone_iso_code')->nullable();
            $table->string('full_phone_number')->nullable();
            $table->enum('salutation', ['Ms', 'Mr', '/']);
            $table->integer('access_right')->nullable();
            $table->string('correspondence_language_code', 5);
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
        Schema::dropIfExists('internal_users');
    }
};
