<?php

use App\Models\ContactDataRecord;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('contact_data_records', function (Blueprint $table) {
            $table->enum('accident', array_column(ContactDataRecord::$accident_lists, 'value'))->nullable()->after('health_insurance');
            $table->enum('franchise',  array_column(ContactDataRecord::$francise_lists, 'value'))->nullable()->after('accident');
            $table->enum('supplementary_insurance',array_column(ContactDataRecord::$supplementary_insurance_lists, 'value'))->nullable()->after('franchise');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('contact_data_records', function (Blueprint $table) {
            $table->dropColumn('accident');
            $table->dropColumn('franchise');
            $table->dropColumn('supplementary_insurance');
        });
    }
};
