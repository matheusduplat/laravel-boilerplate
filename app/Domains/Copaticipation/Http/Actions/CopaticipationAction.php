<?php

namespace App\Domains\Copaticipation\Http\Actions;

use App\Domains\Copaticipation\Http\Resources\CopaticipationResource;
use App\Domains\Copaticipation\Http\Resources\CopaticipationWithPaginationResource;
use App\Domains\Copaticipation\Model\Copaticipation;


class CopaticipationAction
{
    public function query(array $data, bool $is_paginate)
    {
        $copaticipations = Copaticipation::query()
            ->relationWithTrashed()
            ->when(isset($data['customer_id']), function ($query) use ($data) {
                $query->where('customer_id', $data['customer_id']);
            })->when(isset($data['base_year']), function ($query) use ($data) {
                $query->where('base_year', $data['base_year']);
            })->when(isset($data['base_month']), function ($query) use ($data) {
                $query->where('base_month', $data['base_month']);
            });


        if ($is_paginate) {
            return   $copaticipations->paginate($data['per_page'] ?? 10);
        }
        return $copaticipations->get();
    }
    public function execute(array $data, bool $is_paginate)
    {
        $copaticipations = $this->query($data, $is_paginate);

        if ($is_paginate) {
            return new CopaticipationWithPaginationResource($copaticipations);
        }
        return CopaticipationResource::collection($copaticipations);
    }
}
