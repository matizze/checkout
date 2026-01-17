<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model
{
    protected $fillable = [
        'order_id',
        'asaas_id',
        'billing_type',
        'installments',
        'amount',
        'status',
        'due_date',
        'pix_payload',
        'pix_qrcode_base64',
        'paid_at',
    ];

    protected $casts = [
        'amount' => 'integer', // Valor em centavos        'installments' => 'integer',
        'due_date' => 'date',
        'paid_at' => 'datetime',
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }
}
