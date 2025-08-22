<?php

namespace App\Domains\State\Model;

use App\Domains\State\Policy\StatePolicy;
use Illuminate\Database\Eloquent\Attributes\UsePolicy;
use Illuminate\Database\Eloquent\Model;

#[UsePolicy(StatePolicy::class)]
class State extends Model
{
    protected $fillable = ['code', 'name'];
}
