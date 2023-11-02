<?php

namespace App\Http\Resources\User;

use App\Models\CompanyRole;
use App\Models\User;
use Illuminate\Http\Resources\Json\JsonResource;

class LoginResource extends JsonResource
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
            'name'                              =>  $this->name,
            'email'                             =>  $this->email,
            'type'                              =>  $this->type,
            'photo_url'                         =>  $this->photo_url,
            'language'                          =>  $this->language,
            'status'                            =>  $this->status,
            'permissions'                       =>  $this->getAllPermissions(),
            'customer_company_id'               => $this->when($this->customer_company_id, $this->customer_company_id),
            'role'                              => ($this->type == 'internal_user') ? $this->internalUser->companyRole->name : (($this->type == 'broker_user') ? $this->brokerUser->role : $this->type),
            'alignment'                         => $this->when($this->type == 'internal_user', $this->alignmentUser->pluck('alignment_id')),
            //for customer company admins
            // 'customer_company' => $this->when($this->type !== User::SYSTEM_ADMIN && $this->customer_company_id, fn () => $this->customerCompany)
        ];
    }
}
