<?php

namespace App\Observers;

use OpenSearch\Client;
use App\Models\ContactDataRecord;
use App\Models\ContactDataRecordHistory;
use App\Models\ContactDataRecordAppointment;

class ContactDataRecordAppointmentObserver
{
    private $contactDataIndexName;

    public function __construct(public Client $client)
    {
        $this->contactDataIndexName = ContactDataRecord::OPENSEARCH_INDEX_NAME;
    }

    /**
     * Handle the ContactDataRecordAppointment "created" event.
     *
     * @param  \App\Models\ContactDataRecordAppointment  $contactDataRecordAppointment
     * @return void
     */
    public function created(ContactDataRecordAppointment $contactDataRecordAppointment)
    {
        $contactDataRecord = ContactDataRecord::find($contactDataRecordAppointment->contact_data_record_id);

        $history =  $contactDataRecord->contactDataRecordHistories()->create([
            'user_id'   =>  $contactDataRecordAppointment->user_id,
            'action'    =>  ContactDataRecordHistory::APPOINTMENT_ENTRY,
            'status_change' =>  false,
            'old_status' =>  $contactDataRecord->contact_record_status,
            'new_status' => $contactDataRecord->contact_record_status,

            'category_change'   =>  false,
            'old_category'  => $contactDataRecord->category,
            'new_category'  => $contactDataRecord->category,
            'meta'          => $contactDataRecordAppointment
            // 'user_type' =>  $user->type
        ]);


        if(env('OPENSEARCH_ENABLED')){
            $this->client->update([
                'index' => $this->contactDataIndexName,
                'id' => $contactDataRecordAppointment->contact_data_record_id,
                'body' => [
                    'doc'   =>  [
                        'last_appointment' =>  $contactDataRecordAppointment->fresh()
                    ]
                ]
            ]);
        }
    }

    /**
     * Handle the ContactDataRecordAppointment "updated" event.
     *
     * @param  \App\Models\ContactDataRecordAppointment  $contactDataRecordAppointment
     * @return void
     */
    public function updated(ContactDataRecordAppointment $contactDataRecordAppointment)
    {
        //
    }

    /**
     * Handle the ContactDataRecordAppointment "deleted" event.
     *
     * @param  \App\Models\ContactDataRecordAppointment  $contactDataRecordAppointment
     * @return void
     */
    public function deleted(ContactDataRecordAppointment $contactDataRecordAppointment)
    {
        //
    }

    /**
     * Handle the ContactDataRecordAppointment "restored" event.
     *
     * @param  \App\Models\ContactDataRecordAppointment  $contactDataRecordAppointment
     * @return void
     */
    public function restored(ContactDataRecordAppointment $contactDataRecordAppointment)
    {
        //
    }

    /**
     * Handle the ContactDataRecordAppointment "force deleted" event.
     *
     * @param  \App\Models\ContactDataRecordAppointment  $contactDataRecordAppointment
     * @return void
     */
    public function forceDeleted(ContactDataRecordAppointment $contactDataRecordAppointment)
    {
        //
    }
}
