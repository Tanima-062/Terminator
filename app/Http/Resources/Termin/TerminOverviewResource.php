<?php

namespace App\Http\Resources\Termin;

use Illuminate\Http\Resources\Json\JsonResource;

class TerminOverviewResource extends JsonResource
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
            'id'                                =>  $this->id,
            'correspondence_language'           =>  $this->correspondence_language,
            'first_name'                        =>  $this->first_name,
            'last_name'                         =>  $this->last_name,
            'contact_record_status'             =>  $this->contact_record_status,
            'allocation'                        =>  $this->allocation,
            'last_appointment'                  =>  $this->lastAppointment,
        ];
    }
}
