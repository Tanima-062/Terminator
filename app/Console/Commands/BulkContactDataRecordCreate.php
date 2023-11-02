<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Jobs\ContactDataRecords\BulkContactDataRecordCreateJob;

class BulkContactDataRecordCreate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'contactdata:bulk-create {total?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Bulk Contact data record create';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $total = $this->argument('total') ?? 1000;
        for($i= 0; $i < $total; $i++){
            dispatch(new BulkContactDataRecordCreateJob());
        }
    }
}
