<?php

namespace App\Http\Resources\InternalUser;

use Illuminate\Http\Resources\Json\JsonResource;

class InternaluserResource extends JsonResource
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
            'correspondence_language_code'      =>  $this->correspondence_language_code,
            'user'                              =>  $this->whenLoaded('user', fn () => [...$this->user->toArray(), 'name' => $this->user->name]),
            'roles'                             =>  $this->whenLoaded('companyRole', fn () => [$this->companyRole->name]),
        ];
    }
}
