<?php

namespace App\Http\Resources\OrganizationElement;

use Illuminate\Http\Resources\Json\JsonResource;

class OrganizationElementResource extends JsonResource
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
            'id'                 => $this->id,
            'prefix_id'          => $this->prefix_id,
            'name'               => $this->name,
            'status'             => $this->status,
            'type_id'            => $this->type_id,
            'type_name'          => $this->whenLoaded('hierarchyType', $this->hierarchyType->name),
            'created_at'         => $this->created_at,
            'updated_at'         => $this->updated_at,
        ];
    }
}
