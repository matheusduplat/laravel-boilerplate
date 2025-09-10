<?php

namespace App\Domains\Customer\Http\Resources;

use App\Domains\Address\Http\Resources\AddressResource;
use App\Domains\Phone\Http\Resources\PhoneResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CustomerResources extends JsonResource
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
            'name' => $this->name,
            'name_social' => $this->name_social,
            'cpf' => $this->cpf,
            'birth_date' => $this->birth_date?->format('Y-m-d'),
            'birth_date_formated' => $this->birth_date?->format('d/m/Y'),
            'status' => $this->status,
            'status_translated' => $this->status->label(),
            'address' => new AddressResource($this->address),
            'phones' => PhoneResource::collection($this->phones),
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
            'deleted_by' => $this->deleted_by,
            'created_at' => $this->created_at->format('d/m/Y H:i:s'),
            'updated_at' => $this->updated_at?->format('d/m/Y H:i:s'),
            'deleted_at' => $this->deleted_at?->format('d/m/Y H:i:s'),
        ];
    }
}
