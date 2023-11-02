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
        Schema::table('competences', function (Blueprint $table) {
        $table->string('lang_code')->nullable()->change();
        $table->string('other_competence')->nullable()->change();
        $table->string('level')->nullable()->change();
    });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('competences', function (Blueprint $table) {
         $table->string('lang_code')->nullable(false)->change();
        $table->string('other_competence')->nullable(false)->change();
        $table->string('level')->nullable(false)->change();
        });
    }
};
