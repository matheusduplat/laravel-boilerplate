<?php

namespace App\Domains\RequestManagementResponse\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RequestManagementResponseResource extends JsonResource
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
            'author' => $this->authorable,
            'answer' => $this->answer,
            "hidden" => $this->hidden,
            "date_hour" => $this->date_hour?->format('d/m/Y H:i'),
            'request_management_id' => $this->request_management_id,
            'request_management' => $this->request_management,
            'created_at' => $this->created_at->format('d/m/Y H:i:s'),
            'updated_at' => $this->updated_at?->format('d/m/Y H:i:s'),
            'deleted_at' => $this->deleted_at?->format('d/m/Y H:i:s'),
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
            'deleted_by' => $this->deleted_by
        ];
    }
}
