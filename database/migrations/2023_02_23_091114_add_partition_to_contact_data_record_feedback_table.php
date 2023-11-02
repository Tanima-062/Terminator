<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
// use Illuminate\Support\Facades\Schema;
use Brokenice\LaravelMysqlPartition\Schema\Schema;
use Brokenice\LaravelMysqlPartition\Models\Partition;

return new class extends Migration
{
    protected $per_partition = 10000;
    protected $total = 5000000;

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Schema::table('contact_data_record_feedback', function (Blueprint $table) {
        //     //
        // });

        $partitions = [];

        for($i = $this->per_partition; $i<= env('APPROXIMATELY_TOTAL_CONTACT_DATA_RECORDS',  $this->total); $i+= $this->per_partition){
            $partition = new Partition('data'.$i, Partition::RANGE_TYPE, $i);

            $partitions[] = $partition;
        }

        Schema::partitionByRange('contact_data_record_feedback', 'contact_data_record_id', $partitions);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Schema::table('contact_data_record_feedback', function (Blueprint $table) {
        //     //
        // });

        $partitions = [];
        for($i = $this->per_partition; $i<= env('APPROXIMATELY_TOTAL_CONTACT_DATA_RECORDS',  $this->total); $i+= $this->per_partition){
            $partitions[] = 'data'.$i;
        }
        Schema::deletePartition('contact_data_record_feedback',  $partitions);
    }
};
