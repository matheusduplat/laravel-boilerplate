<?php

namespace App\Trait;

use App\Domains\User\Model\User;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

trait GlobalRelationship
{
    /**
     * Relacionamento create
     *
     * @return BelongsTo
     */
    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by')->withTrashed();
    }
    /**
     * Relacionamento update
     *
     * @return BelongsTo
     */
    public function updatedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by')->withTrashed();
    }
    /**
     * Relacionamento delete
     *
     * @return BelongsTo
     */
    public function deletedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'deleted_by')->withTrashed();
    }
}
