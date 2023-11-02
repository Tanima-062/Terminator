<?php

namespace App\Observers;

use App\Models\ContactDataRecord;
use OpenSearch\Client;
use App\Models\ContactDataRecordFeedback;

class ContactDataRecordFeedbackObserver
{

    private $contactDataIndexName;

    public function __construct(public Client $client)
    {
        $this->contactDataIndexName = ContactDataRecord::OPENSEARCH_INDEX_NAME;
    }

    /**
     * Handle the ContactDataRecordFeedback "created" event.
     *
     * @param  \App\Models\ContactDataRecordFeedback  $contactDataRecordFeedback
     * @return void
     */
    public function created(ContactDataRecordFeedback $contactDataRecordFeedback)
    {
        if(env('OPENSEARCH_ENABLED')){

            $all_feedbacks = ContactDataRecordFeedback::query()
                ->where('contact_data_record_id', $contactDataRecordFeedback->contact_data_record_id)
                ->pluck('feedback')
                ->toArray();
            // info('observer calling');
            $this->client->update([
                'index' => $this->contactDataIndexName,
                'id' => $contactDataRecordFeedback->contact_data_record_id,
                'body' => [
                    'doc'   =>  [
                        'last_feedback' =>  $contactDataRecordFeedback->feedback,
                        'last_feedback_time' =>  $contactDataRecordFeedback->created_at,
                        'feedbacks'         =>  $all_feedbacks,
                        // 'last_feedback' =>  $contactDataRecordFeedback
                    ]
                ]
            ]);
        }
    }

    /**
     * Handle the ContactDataRecordFeedback "updated" event.
     *
     * @param  \App\Models\ContactDataRecordFeedback  $contactDataRecordFeedback
     * @return void
     */
    public function updated(ContactDataRecordFeedback $contactDataRecordFeedback)
    {
        //
    }

    /**
     * Handle the ContactDataRecordFeedback "deleted" event.
     *
     * @param  \App\Models\ContactDataRecordFeedback  $contactDataRecordFeedback
     * @return void
     */
    public function deleted(ContactDataRecordFeedback $contactDataRecordFeedback)
    {
        //
    }

    /**
     * Handle the ContactDataRecordFeedback "restored" event.
     *
     * @param  \App\Models\ContactDataRecordFeedback  $contactDataRecordFeedback
     * @return void
     */
    public function restored(ContactDataRecordFeedback $contactDataRecordFeedback)
    {
        //
    }

    /**
     * Handle the ContactDataRecordFeedback "force deleted" event.
     *
     * @param  \App\Models\ContactDataRecordFeedback  $contactDataRecordFeedback
     * @return void
     */
    public function forceDeleted(ContactDataRecordFeedback $contactDataRecordFeedback)
    {
        //
    }
}
