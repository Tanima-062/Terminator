<?php

namespace App\Http\Resources\ContactDataRecords;

use Illuminate\Http\Resources\Json\JsonResource;

class TerminTabResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id'        =>  $this->id,
            // 'prefix_id'        =>  $this->prefix_id,
            // 'created_at'        =>  $this->created_at,
            // 'user_id'        =>  $this->user_id,
            'correspondence_language'        =>  $this->correspondence_language,
            'canton'        =>  $this->canton,
            'first_name'        =>  $this->first_name,
            'last_name'        =>  $this->last_name,
            // 'lead'        =>  $this->lead,
            'zip_code'        =>  $this->zip_code,
            'city'        =>  $this->city,
            'contact_record_status'        =>  $this->contact_record_status,
            'number_of_persons_in_household'        =>  $this->number_of_persons_in_household,
            // 'creator'        =>  $this->creator,
            // 'last_feedback_id'        =>  $this->last_feedback_id,
            // 'last_feedback'        =>  $this->lastFeedback,
            'allocation'        =>  $this->allocation,
            'last_appointment'        =>  $this->lastAppointment,
            'campaign'        =>  $this->campaign,
        ];
    }
}
