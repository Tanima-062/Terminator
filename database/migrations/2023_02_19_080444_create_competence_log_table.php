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
        Schema::create('competence_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('competence_id')->constrained('competences', 'id');
            $table->foreignId('created_by')->constrained('users', 'id');
            $table->enum('status', ['confirmed', 'not_confirmed'])->default('confirmed');
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
        Schema::dropIfExists('competence_log');
    }
};
