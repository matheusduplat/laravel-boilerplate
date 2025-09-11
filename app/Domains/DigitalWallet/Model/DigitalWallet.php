<?php

namespace App\Domains\DigitalWallet\Model;

use App\Domains\DigitalWallet\Policy\DigitalWalletPolicy;
use App\Domains\DigitalWallet\Traits\DigitalWalletMethod;
use App\Domains\DigitalWallet\Traits\DigitalWalletRelationship;
use App\Domains\DigitalWallet\Traits\DigitalWalletScope;
use App\Trait\GlobalSoftDelete;
use Database\Factories\DigitalWalletFactory;
use Illuminate\Database\Eloquent\Attributes\UsePolicy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use \OwenIt\Auditing\Auditable as AuditableTrait;

#[UsePolicy(DigitalWalletPolicy::class)]
class DigitalWallet extends Model implements Auditable
{
    use DigitalWalletRelationship, DigitalWalletMethod, DigitalWalletScope;
    use GlobalSoftDelete, HasFactory, AuditableTrait;

    protected  $table = 'digital_wallets';
    protected $fillable = [
        'customer_id',
        'plan',
        'number',
        'date_issue',
        'validity',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    protected $casts = [
        'plan' => 'json',
        'validity' => 'date',
        'date_issue' => 'date',
    ];
    protected static function newFactory()
    {
        return DigitalWalletFactory::new();
    }
}
