<?php

namespace App\Domains\CodeVerification\Model;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use \OwenIt\Auditing\Auditable as AuditableTrait;

class CodeVerification extends Model implements Auditable
{
    use  AuditableTrait;
    protected $table = 'code_verifications';
    protected $fillable = ['email', 'code', 'expires_at'];

    protected $casts = [
        'expires_at' => 'datetime',
    ];
}
