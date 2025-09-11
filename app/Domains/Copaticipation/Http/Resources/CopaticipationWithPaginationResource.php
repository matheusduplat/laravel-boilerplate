<?php

namespace App\Domains\Copaticipation\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CopaticipationWithPaginationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'items' => CopaticipationResource::collection($this->resource->items()),
            'pagination_more' => $this->resource->hasMorePages(),
            'perPage' => $this->resource->perPage(),
            'total' => $this->resource->total(),
            'lastPage' => $this->resource->lastPage(),
            'currentPage' => $this->resource->currentPage(),
        ];
    }
}
