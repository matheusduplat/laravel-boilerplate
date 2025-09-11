<?php

namespace App\Domains\Bill\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Number;

class BillResource extends JsonResource
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
            // 'base64' => $this->base64,
            'title_id' => $this->title_id,
            'base_year' => $this->base_year,
            'base_month' => $this->base_month,
            'issue_date' => $this->issue_date->format('d/m/Y'),
            'due_date' => $this->due_date->format('d/m/Y'),
            'low_date' => $this->low_date?->format('d/m/Y'),
            'value' => Number::currency($this->value / 100, 'BRL', 'pt', 2),
            'status' => $this->status,
            'status_translated' => $this->status->label(),
            'customer' => $this->customer,
            'created_by' => $this->created_by,
            'created_at' => $this->created_at->format('d/m/Y H:i:s'),
            'updated_by' => $this->updated_by,
            'updated_at' => $this->updated_at?->format('d/m/Y H:i:s'),
            'deleted_by' => $this->deleted_by,
            'deleted_at' => $this->deleted_at?->format('d/m/Y H:i:s'),
        ];
    }
}
