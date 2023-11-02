<?php

namespace App\Jobs\ContactDataRecords;

use App\Models\ContactDataRecord;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class BulkContactDataRecordCreateJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $timeout = 600;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->connection = 'bulk-contact-data-create';
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        ContactDataRecord::factory()->count(1000)->newStatus()->create();
        // sleep(1);
    }
}
