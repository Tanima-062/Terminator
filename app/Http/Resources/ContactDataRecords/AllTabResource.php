<?php

namespace App\Http\Resources\ContactDataRecords;

use Illuminate\Http\Resources\Json\JsonResource;

class AllTabResource extends JsonResource
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
            'id'                        =>  $this->id,
            'prefix_id'                 =>  $this->prefix_id,
            'created_at'                =>  $this->created_at,
            'category'                  =>  $this->category,
            'correspondence_language'   =>  $this->correspondence_language,
            'first_name'                =>  $this->first_name,
            'last_name'                 =>  $this->last_name,
            'lead'                      =>  $this->lead,
            'contact_record_status'     =>  $this->contact_record_status,
            'allocation'                =>  $this->allocation,
            'last_appointment'          =>  $this->lastAppointment,
            'campaign'                  =>  $this->campaign,
        ];
    }
}
