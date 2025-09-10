<?php

namespace App\Domains\Employee\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ComponentSelectEmployeeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'items' => collect($this->resource->items())->map(function ($item) {
                return [
                    'value' => $item->id,
                    'label' => $item->name,
                ];
            }),
            'pagination_more' => $this->resource->hasMorePages(),
            'perPage' => $this->resource->perPage(),
            'total' => $this->resource->total(),
            'lastPage' => $this->resource->lastPage(),
            'currentPage' => $this->resource->currentPage(),
        ];
    }
}
