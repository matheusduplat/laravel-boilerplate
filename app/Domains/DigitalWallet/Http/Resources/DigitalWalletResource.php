<?php

namespace App\Domains\DigitalWallet\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DigitalWalletResource extends JsonResource
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
            'plan' => $this->plan,
            'number' => $this->number,
            'date_issue' => $this->date_issue,
            'date_issue_format' => $this->date_issue->format('d/m/Y'),
            'validity' => $this->validity,
            'validity_format' => $this->validity?->format('d/m/Y'),
            'customer' => $this->customer,
            'created_at' => $this->created_at->format('d/m/Y'),
            'updated_at' => $this->updated_at?->format('d/m/Y'),
            'deleted_at' => $this->deleted_at?->format('d/m/Y'),
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
            'deleted_by' => $this->deleted_by
        ];
    }
}
