<?php

namespace App\Http\Resources\Broker;

use Illuminate\Http\Resources\Json\JsonResource;

class BrokerResource extends JsonResource
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
            'name'                      =>  $this->name,
            'created_at'                =>  $this->created_at,
            'customer_company'          =>  $this->whenLoaded('customerCompany', fn()=> $this->customerCompany),
            'contact_person_full_name'  =>  $this->contact_person_full_name,
            'status'                    =>  $this->status
        ];
    }
}
