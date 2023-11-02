<?php

namespace App\Observers;

use OpenSearch\Client;
use App\Models\ContactDataRecord;
use App\Models\ContactDataRecordHistory;
use App\Models\ContactDataRecordAllocate;

class ContactDataRecordAllocationObserver
{
    private $contactDataIndexName;

    public function __construct(public Client $client)
    {
        $this->contactDataIndexName = ContactDataRecord::OPENSEARCH_INDEX_NAME;
    }

    /**
     * Handle the ContactDataRecordAllocate "created" event.
     *
     * @param  \App\Models\ContactDataRecordAllocate  $contactDataRecordAllocate
     * @return void
     */
    public function created(ContactDataRecordAllocate $contactDataRecordAllocate)
    {
        $contactDataRecord = ContactDataRecord::find($contactDataRecordAllocate->contact_data_record_id);
        // info($contactDataRecordAllocate);
        // info($contactDataRecord);

        $history =  $contactDataRecord->contactDataRecordHistories()->create([
            'user_id'   =>  $contactDataRecordAllocate->allocate_by_user_id,
            'action'    =>  ContactDataRecordHistory::ALLOCATION,
            'status_change' =>  false,
            'old_status' =>  $contactDataRecord->contact_record_status,
            'new_status' => $contactDataRecord->contact_record_status,

            'category_change'   =>  false,
            'old_category'  => $contactDataRecord->category,
            'new_category'  => $contactDataRecord->category,
            // 'user_type' =>  $user->type
        ]);


        if(env('OPENSEARCH_ENABLED')){
            // info('observer calling');
            $this->client->update([
                'index' => $this->contactDataIndexName,
                'id' => $contactDataRecordAllocate->contact_data_record_id,
                'body' => [
                    'doc'   =>  [
                        'allocation' =>  $contactDataRecordAllocate->fresh(),
                    ]
                ]
            ]);
        }
    }

    /**
     * Handle the ContactDataRecordAllocate "updated" event.
     *
     * @param  \App\Models\ContactDataRecordAllocate  $contactDataRecordAllocate
     * @return void
     */
    public function updated(ContactDataRecordAllocate $contactDataRecordAllocate)
    {
        //
    }

    /**
     * Handle the ContactDataRecordAllocate "deleted" event.
     *
     * @param  \App\Models\ContactDataRecordAllocate  $contactDataRecordAllocate
     * @return void
     */
    public function deleted(ContactDataRecordAllocate $contactDataRecordAllocate)
    {
        //
    }

    /**
     * Handle the ContactDataRecordAllocate "restored" event.
     *
     * @param  \App\Models\ContactDataRecordAllocate  $contactDataRecordAllocate
     * @return void
     */
    public function restored(ContactDataRecordAllocate $contactDataRecordAllocate)
    {
        //
    }

    /**
     * Handle the ContactDataRecordAllocate "force deleted" event.
     *
     * @param  \App\Models\ContactDataRecordAllocate  $contactDataRecordAllocate
     * @return void
     */
    public function forceDeleted(ContactDataRecordAllocate $contactDataRecordAllocate)
    {
        //
    }
}
