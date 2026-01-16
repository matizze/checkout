<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Customer extends Model
{
    protected $fillable = [
        'asaas_id',
        'name',
        'email',
        'cpf_cnpj',
        'phone',
        'postal_code',
        'address',
        'address_number',
        'province',
        'city',
        'state',
    ];

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }
}
