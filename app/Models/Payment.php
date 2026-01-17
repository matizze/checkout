<?php

namespace App\Models;

use App\Casts\MoneyCast;
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

    protected function casts(): array
    {
        return [
            'amount' => MoneyCast::class,
            'installments' => 'integer',
            'due_date' => 'date',
            'paid_at' => 'datetime',
        ];
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }
}
