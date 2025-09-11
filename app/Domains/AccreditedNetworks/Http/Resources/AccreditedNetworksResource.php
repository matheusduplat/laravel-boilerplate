<?php

namespace App\Domains\AccreditedNetworks\Http\Resources;

use App\Domains\Address\Http\Resources\AddressResource;
use App\Domains\Phone\Http\Resources\PhoneResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AccreditedNetworksResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "id" => $this->id,
            "name" => $this->name,
            "type_service" => $this->type_service,
            "type_service_translated" => $this->type_service->label(),
            "specialties_served" => $this->specialties_served,
            'exams_attended' => $this->exams_attended,
            "plans_attended" => $this->plans_attended,
            "urgency_emergency" => $this->urgency_emergency,
            "status" => $this->status,
            "status_translated" => $this->status->label(),
            "contacts" => PhoneResource::collection($this->phones),
            "address" => new AddressResource($this->address),
            "created_at" => $this->created_at->format('d/m/Y H:i:s'),
            "updated_at" => $this->updated_at?->format('d/m/Y H:i:s'),
            "deleted_at" => $this->deleted_at?->format('d/m/Y H:i:s'),
            "created_by" => $this->created_by,
            "updated_by" => $this->updated_by,
            "deleted_by" => $this->deleted_by
        ];
    }
}
