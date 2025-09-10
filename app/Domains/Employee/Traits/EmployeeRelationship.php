<?php

namespace App\Domains\Employee\Traits;

use App\Domains\Phone\Model\Phone;
use App\Domains\User\Model\User;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;

trait EmployeeRelationship
{

    public function user(): MorphOne
    {
        return $this->morphOne(User::class, 'userable');
    }
    public function userWithTrashed(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    public function phones(): MorphMany
    {
        return $this->morphMany(Phone::class, 'phoneable');
    }
}
