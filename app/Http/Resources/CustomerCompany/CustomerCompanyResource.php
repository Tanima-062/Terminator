<?php

namespace App\Http\Resources\CustomerCompany;

use Illuminate\Http\Resources\Json\JsonResource;

class CustomerCompanyResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return  [
            'id'                        =>  $this->id,
            'prefix_id'                 =>  $this->prefix_id,
            'name'                      =>  $this->name,
            'alias_name'                =>  $this->alias_name,
            'contact_person_name'       =>  $this->contact_person_name,
            'status'                    =>  $this->status,
            'created_at'                =>  $this->created_at,
        ];
    }
}
