<?php

namespace App\Http\Resources\ContactDataRecords;

use Illuminate\Http\Resources\Json\JsonResource;

class LeadsTabResource extends JsonResource
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
            'prefix_id'        =>  $this->prefix_id,
            'created_at'        =>  $this->created_at,
            'user_id'        =>  $this->user_id,
            'correspondence_language'        =>  $this->correspondence_language,
            'canton'        =>  $this->canton,
            'first_name'        =>  $this->first_name,
            'last_name'        =>  $this->last_name,
            'lead'        =>  $this->lead,
            'date_of_birth'        =>  $this->date_of_birth,
            'contact_record_status'        =>  $this->contact_record_status,
            'last_feedback_id'        =>  $this->last_feedback_id,
            'campaign'        =>  $this->campaign,
            'creator'        =>  $this->creator,
            'last_feedback'        =>  $this->lastFeedback,
            'allocation'        =>  $this->allocation,
        ];
    }
}
