<?php

namespace App\Jobs\ContactDataRecords;

use Illuminate\Bus\Queueable;
use App\Models\ContactDataRecord;
use Illuminate\Queue\SerializesModels;
use App\Models\ContactDataRecordHistory;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Contracts\Queue\ShouldBeUnique;

class CheckDuplicateJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(public ContactDataRecord $contactDataRecord, public $user_id)
    {

    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
    //    $history =  $this->contactDataRecord->contactDataRecordHistories()->create([
    //         'user_id'   =>  $this->user_id,
    //         'action'    =>  ContactDataRecordHistory::CONTACT_DATA_RECORD_CREATION,
    //         'status_change' =>  true,
    //         'old_status' =>  null,
    //         'new_status' =>  $this->contactDataRecord->contact_record_status,

    //         'category_change'   =>  true,
    //         'old_category'  => null,
    //         'new_category'  => $this->contactDataRecord->category,
    //         // 'user_type' =>  $user->type
    //     ]);
        // 'old_status', 'new_status', 'old_category', 'new_category',

        // dump($history);

        $oldRecord = ContactDataRecord::where('phone_number', $this->contactDataRecord->phone_number)
            ->where('id', '!=', $this->contactDataRecord->id)
            ->orderBy('id', 'ASC')
            ->first()
            ;

        if($oldRecord){
            ContactDataRecord::where('id', $this->contactDataRecord->id)->update([
                'contact_record_status' =>  'Check Duplicate'
            ]);

            $this->contactDataRecord->contactDataRecordHistories()->create([
                'user_id'   =>  1,
                'action'    =>  ContactDataRecordHistory::POSSIBLE_DUPLICATED_DETECTED,
                'status_change' =>  true,
                'old_status' =>  $this->contactDataRecord->contact_record_status,
                'new_status' =>  'Check Duplicate',
                'category_change'=>false,
                'old_category'  => null,
                'new_category'  => null,
                // 'user_type' =>  $user->type
            ]);

            if( strtolower($oldRecord->contact_record_status) == strtolower('New')){
                $oldRecord->update([
                    'contact_record_status' =>  'Check Duplicate'
                ]);

                $oldRecord->contactDataRecordHistories()->create([
                    'user_id'   =>  1,
                    'action'    =>  ContactDataRecordHistory::POSSIBLE_DUPLICATED_DETECTED,
                    'status_change' =>  true,
                    'old_status' =>  $this->contactDataRecord->contact_record_status,
                    'new_status' =>  'Check Duplicate',
                    'category_change'=>false,
                    'old_category'  => null,
                    'new_category'  => null,
                    // 'user_type' =>  $user->type
                ]);
            }
        }
    }
}
