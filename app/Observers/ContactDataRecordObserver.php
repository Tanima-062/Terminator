<?php

namespace App\Observers;

use App\Models\User;
use App\Models\ContactDataRecord;
use App\Models\ContactDataRecordHistory;
use App\Jobs\ContactDataRecords\CheckDuplicateJob;
use App\Models\ContactDataRecordFeedback;
use OpenSearch\Client;

class ContactDataRecordObserver
{
    private $indexName;

    public function __construct(public Client $client)
    {
        $this->indexName = ContactDataRecord::OPENSEARCH_INDEX_NAME;
    }
    /**
     * Handle the ContactDataRecord "creatinng" event.
     *
     * @param  \App\Models\ContactDataRecord  $contactDataRecord
     * @return void
     */
    public function creating(ContactDataRecord $contactDataRecord)
    {
        // $last_id = 1;

        // if($last_item = ContactDataRecord::where('customer_company_id', $contactDataRecord->customer_company_id)->orderBy('id', 'DESC')->first()){
        //     if($last_item->prefix_id && preg_match("%([1-9]\d?.*?)$%", $last_item->prefix_id, $matches)){
        //         $last_id = ((int) $matches[1]) + 1;
        //     }
        // }

        // $prefix =  sprintf("%s%05d", ContactDataRecord::PREFIX, $last_id);

        // $contactDataRecord->prefix_id = $prefix;
        // $contactDataRecord->prefix_id = ContactDataRecord::generatePrefixId($contactDataRecord);

        $contactDataRecord->contact_record_status = $contactDataRecord->contact_record_status?? 'New';

        $contactDataRecord->full_phone_number = "+".getCountryCodeByAbbreviation($contactDataRecord->phone_number_iso_code).$contactDataRecord->phone_number;

    }
    /**
     * Handle the ContactDataRecord "created" event.
     *
     * @param  \App\Models\ContactDataRecord  $contactDataRecord
     * @return void
     */
    public function created(ContactDataRecord $contactDataRecord)
    {

        // $user = User::find($contactDataRecord->user_id);

        // $contactDataRecord->contactDataRecordHistories()->create([
        //     'user_id'   =>  $contactDataRecord->user_id,
        //     'action'    =>  ContactDataRecordHistory::CONTACT_DATA_RECORD_CREATION,
        //     'status_change' =>  $contactDataRecord->contact_record_status,
        //     'category_change' =>  $contactDataRecord->category,
        //     // 'user_type' =>  $user->type
        // ]);

        // $oldRecord = ContactDataRecord::where('phone_number', $contactDataRecord->phone_number)->orderBy('id', 'ASC')->first();
        // if($oldRecord){
        //     ContactDataRecord::where('id',$contactDataRecord->id)->update([
        //         'contact_record_status' =>  'Check Duplicate'
        //     ]);

        //     if( strtolower($oldRecord->contact_record_status) == strtolower('New')){
        //         $oldRecord->update([
        //             'contact_record_status' =>  'Check Duplicate'
        //         ]);
        //     }
        // }

        // $contactDataRecord->update([
        //     'secondary_id'  =>   $contactDataRecord->id
        // ]);

        $history =  $contactDataRecord->contactDataRecordHistories()->create([
            'user_id'   =>  $contactDataRecord->user_id,
            'action'    =>  ContactDataRecordHistory::CONTACT_DATA_RECORD_CREATION,
            'status_change' =>  true,
            'old_status' =>  null,
            'new_status' =>  $contactDataRecord->contact_record_status,

            'category_change'   =>  true,
            'old_category'  => null,
            'new_category'  => $contactDataRecord->category,
            'notes'          =>  $contactDataRecord->remarks_control_lead
            // 'user_type' =>  $user->type
        ]);

        if(env('OPENSEARCH_ENABLED')){
            $this->client->create([
                'index' => $this->indexName,
                'id' => $contactDataRecord->id,
                'body' => $this->generateData($contactDataRecord)
            ]);
        }

        dispatch(new CheckDuplicateJob($contactDataRecord, $contactDataRecord->user_id));
    }

    /**
     * Handle the ContactDataRecord "updated" event.
     *
     * @param  \App\Models\ContactDataRecord  $contactDataRecord
     * @return void
     */
    public function updating(ContactDataRecord $contactDataRecord)
    {
    //    dd($contactDataRecord);

        // if(env('OPENSEARCH_ENABLED')){
        //     $this->client->update([
        //         'index' => $this->indexName,
        //         'id' => $contactDataRecord->id,
        //         'body' => [
        //             'doc'   =>  $this->generateData($contactDataRecord)
        //         ]
        //     ]);
        // }
    }
    /**
     * Handle the ContactDataRecord "updated" event.
     *
     * @param  \App\Models\ContactDataRecord  $contactDataRecord
     * @return void
     */
    public function updated(ContactDataRecord $contactDataRecord)
    {
        info('update', [$contactDataRecord]);

        if(env('OPENSEARCH_ENABLED')){
            $this->client->update([
                'index' => $this->indexName,
                'id' => $contactDataRecord->id,
                'body' => [
                    'doc'   =>  $this->generateData($contactDataRecord)
                ]
            ]);
        }
    }

    /**
     * Handle the ContactDataRecord "deleted" event.
     *
     * @param  \App\Models\ContactDataRecord  $contactDataRecord
     * @return void
     */
    public function deleted(ContactDataRecord $contactDataRecord)
    {
        //
    }

    /**
     * Handle the ContactDataRecord "restored" event.
     *
     * @param  \App\Models\ContactDataRecord  $contactDataRecord
     * @return void
     */
    public function restored(ContactDataRecord $contactDataRecord)
    {
        //
    }

    /**
     * Handle the ContactDataRecord "force deleted" event.
     *
     * @param  \App\Models\ContactDataRecord  $contactDataRecord
     * @return void
     */
    public function forceDeleted(ContactDataRecord $contactDataRecord)
    {

    }


    private function generateData(ContactDataRecord $contactDataRecord)
    {
        // $last_feedback = ContactDataRecordFeedback::query()
        //     ->where('contact_data_record_id', $contactDataRecord->id)
        //     ->latest()
        //     ->first();
        // info('calling');
        $contactDataRecord =  $contactDataRecord->fresh();

        return [
            'id'        =>  $contactDataRecord->id,
            'campaign_id' =>    $contactDataRecord->campaign_id,
            'customer_company_id' =>    $contactDataRecord->customer_company_id,
            'user_id' =>    $contactDataRecord->user_id,
            'prefix_id' =>  $contactDataRecord->prefix_id,
            'source' => $contactDataRecord->source,
            'category' =>   $contactDataRecord->category,
            'salutation' => $contactDataRecord->salutation,
            'first_name' => $contactDataRecord->first_name,
            'last_name' =>  $contactDataRecord->last_name,
            'full_name' =>  $contactDataRecord->full_name,
            'date_of_birth' =>  $contactDataRecord->date_of_birth,
            'phone_number' =>   $contactDataRecord->phone_number,
            'phone_number_iso_code' =>  $contactDataRecord->phone_number_iso_code,
            'full_phone_number' =>  $contactDataRecord->full_phone_number,
            'email' =>  $contactDataRecord->email,
            'street' => $contactDataRecord->street,
            'house_number' =>   $contactDataRecord->house_number,
            'zip_code' =>   $contactDataRecord->zip_code,
            'city' =>   $contactDataRecord->city,
            'country_iso_code' =>   $contactDataRecord->country_iso_code,
            'canton' => $contactDataRecord->canton,
            'region' => $contactDataRecord->region,
            'other_languages' =>    $contactDataRecord->other_languages,
            'correspondence_language' =>    $contactDataRecord->correspondence_language,
            'car_insurance' =>  $contactDataRecord->car_insurance,
            'third_piller' =>   $contactDataRecord->third_piller,
            'household_goods' =>    $contactDataRecord->household_goods,
            'legal_protection' =>   $contactDataRecord->legal_protection,
            'health_status' =>  $contactDataRecord->health_status,
            'contact_person_for_insurance_questions' => $contactDataRecord->contact_person_for_insurance_questions,
            'health_insurance' =>   $contactDataRecord->health_insurance,
            'accident' =>   $contactDataRecord->accident,
            'franchise' =>   $contactDataRecord->franchise,
            'supplementary_insurance' =>   $contactDataRecord->supplementary_insurance,
            'save' =>   $contactDataRecord->save,
            'last_health_insurance_change' =>   $contactDataRecord->last_health_insurance_change,
            'satisfaction' =>   $contactDataRecord->satisfaction,
            'number_of_persons_in_household' => $contactDataRecord->number_of_persons_in_household,
            'work_activity' =>  $contactDataRecord->work_activity,
            'desired_consultation_channel' =>   $contactDataRecord->desired_consultation_channel,
            'competition' =>    $contactDataRecord->competition,
            'origin_link' =>    $contactDataRecord->origin_link,
            'contact_desired' =>    $contactDataRecord->contact_desired,
            'lead' =>   $contactDataRecord->lead,
            'remarks_control_lead' =>   $contactDataRecord->remarks_control_lead,
            'remarks_control_appointment' =>   $contactDataRecord->remarks_control_appointment,
            'data_verified_updated' =>  $contactDataRecord->data_verified_updated,
            'contact_record_status' =>  $contactDataRecord->contact_record_status,
            'creator'       =>  [
                'id'        =>  $contactDataRecord->creator->id,
                'full_name' =>  $contactDataRecord->creator->full_name,
                'email'     =>  $contactDataRecord->creator->email
            ],
            'allocation'    =>  $contactDataRecord->allocation,
            // 'allocation'    =>  $contactDataRecord->allocation,
            // 'last_feedback' =>  $contactDataRecord->lastFeedback,
            // 'last_feedback'  => $last_feedback?->feedback,
            // 'last_feedback_time'  => $last_feedback?->created_at,
            'last_feedback'  => null,
            'last_feedback_time'  => null,
            'campaign'      =>  $contactDataRecord->campaign,
            'created_at'    =>  $contactDataRecord->created_at,
            'last_appointment'=> null,
        ];
    }
}
