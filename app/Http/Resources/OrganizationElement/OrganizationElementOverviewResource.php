<?php

namespace App\Http\Resources\OrganizationElement;

use Illuminate\Http\Resources\Json\JsonResource;

class OrganizationElementOverviewResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $responsible_users = $this->responsibleRoleUsers->map(fn ($roleUser) => ['full_name' => $roleUser->user->name, 'id' => $roleUser->user->internalUser->id])->sortBy('full_name')->toArray();
        return [
            'id'                            => $this->id,
            'prefix_id'                     => $this->prefix_id,
            'name'                          => $this->name,
            'status'                        => $this->status,
            'type_id'                       => $this->type_id,
            'hierarchy_name'                => $this->hierarchy_name,
            'customer_company_name'         => $this->customer_company_name,
            'customer_company_id'           => $this->customer_company_id,
            'created_at'                    => $this->created_at,
            'updated_at'                    => $this->updated_at,
            'responsible_users'             => $responsible_users,
        ];
    }
}
