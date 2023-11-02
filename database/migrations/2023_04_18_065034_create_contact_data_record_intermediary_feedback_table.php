<?php

use App\Models\ContactDataRecord;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Traits\ContactDataRecords\DropdownOption;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contact_data_record_intermediary_feedback', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(ContactDataRecord::class);
            $table->boolean('appointment_took_place');
            $table->enum('outcome', ['Positive', 'Negative', 'Follow up contact necessary'])->nullable();
            $table->integer('contracts_concluded')->nullable();
            $table->string('intermediary_remarks', 250)->nullable();
            $table->enum('reason', array_column(DropDownOption::$resones, 'value'))->nullable();
            $table->string('other', 250)->nullable();
            $table->date('expiry_year')->nullable();
            $table->date('follow_up_contact_date')->nullable();
            $table->time('follow_up_contact_time')->nullable();
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
        Schema::dropIfExists('contact_data_record_intermediary_feedback');
    }
};
