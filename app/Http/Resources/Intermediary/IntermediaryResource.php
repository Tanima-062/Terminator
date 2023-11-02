<?php

namespace App\Http\Resources\Intermediary;

use Illuminate\Http\Resources\Json\JsonResource;

class IntermediaryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return    [
            'id'                      => $this->id,
            'created_at'              => $this->created_at,
            'correspondence_language' => $this->correspondence_language,
            'user'                    => $this->whenLoaded('user', fn()=> $this->user),
            'role'                    => $this->role,
        ];
    }
}
