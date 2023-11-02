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
        Schema::create('competences', function (Blueprint $table) {
            $table->id();
            $table->string('prefix_id');
            $table->foreignId('user_id')->constrained('users', 'id');
            $table->string('type');
            $table->string('lang_code');
            $table->string('other_competence');
            $table->string('level');
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
        Schema::dropIfExists('competence');
    }
};
