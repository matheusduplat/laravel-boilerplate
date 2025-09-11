<?php

namespace App\Domains\RequestManagement\Http\Resources;

use App\Domains\RequestManagementResponse\Http\Resources\RequestManagementResponseResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class RequestManagementResource extends JsonResource
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
            "code" => $this->code,
            'type' => $this->type,
            'type_translated' => $this->type->label(),
            'note_customer' => $this->note_customer,
            'location_performing_procedure' => $this->location_performing_procedure,
            'attachment' => $this->attachment,
            'attachment_url' => $this->attachment ? Storage::url($this->attachment) : null,
            'code_guide' => $this->code_guide,
            'customer' => $this->customer,
            'responses' =>  RequestManagementResponseResource::collection($this->responses),
            'responsible' => $this->responsible,
            'status' => $this->status,
            'status_translated' => $this->status->label(),
            'status_procedure' => $this->status_procedure,
            'status_procedure_translated' => $this->status_procedure?->label(),
            "date_close" => $this->date_close?->format('Y-m-d H:i'),
            "date_close_formated" => $this->date_close?->format('d/m/Y H:i'),
            'created_at' => $this->created_at->format('d/m/Y H:i:s'),
            'updated_at' => $this->updated_at?->format('d/m/Y H:i:s'),
            'deleted_at' => $this->deleted_at?->format('d/m/Y H:i:s'),
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
            'deleted_by' => $this->deleted_by
        ];
    }
}
