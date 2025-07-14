<?php

namespace App\Domains\CodeVerification\Model;

use Illuminate\Database\Eloquent\Model;

class CodeVerification extends Model
{
    protected $table = 'code_verifications';
    protected $fillable = ['email', 'code', 'expires_at'];

    protected $casts = [
        'expires_at' => 'datetime',
    ];
}
