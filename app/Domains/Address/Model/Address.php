<?php

namespace App\Domains\Address\Model;

use App\Domains\Address\Traits\AddressRelationship;
use App\Trait\GlobalSoftDelete;
use Database\Factories\AddressFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use \OwenIt\Auditing\Auditable as AuditableTrait;

class Address extends Model implements Auditable
{
    use GlobalSoftDelete, AuditableTrait, AddressRelationship, HasFactory;
    protected $table = 'addresses';
    protected $fillable = [
        'zip_code',
        'state',
        'city',
        'neighborhood',
        'street',
        'number',
        'complement',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    protected static function newFactory()
    {
        return AddressFactory::new();
    }
}
