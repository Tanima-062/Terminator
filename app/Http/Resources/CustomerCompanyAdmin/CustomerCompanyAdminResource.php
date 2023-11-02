<?php

namespace App\Http\Resources\CustomerCompanyAdmin;

use Illuminate\Http\Resources\Json\JsonResource;

class CustomerCompanyAdminResource extends JsonResource
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
            'id'                                =>  $this->id,
            'prefix_id'                         =>  $this->prefix_id,
            'created_at'                        =>  $this->created_at,
            'full_phone_number'                 =>  $this->full_phone_number,
            'customer_company'                  =>  $this->whenLoaded('customerCompany', fn()=> $this->customerCompany),
            'user'                              =>  $this->whenLoaded('user', fn()=> [...$this->user->toArray(), 'name'=>$this->user->name]),
        ];
    }
}
