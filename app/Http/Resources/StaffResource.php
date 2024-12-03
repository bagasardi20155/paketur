<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StaffResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'address' => $this->whenNotNull($this->name),
            'hp' => $this->whenNotNull($this->email),
            'created_at' => $this->whenNotNull($this->created_at),
            'updated_at' => $this->whenNotNull($this->updated_at),
            'deleted_at' => $this->whenNotNull($this->deleted_at),
            'company_id' => $this->whenNotNull($this->company_id),
            'company' => CompanyResource::collection($this->whenLoaded('company')),
            'user_id' => $this->whenNotNull($this->user_id),
            'user' => UserResource::collection($this->whenLoaded('user')),
        ];
    }
}
