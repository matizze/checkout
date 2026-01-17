<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Product extends Model
{
    protected $fillable = [
        'name',
        'description',
        'image',
        'price',
    ];

    public function getImageUrlAttribute(): string
    {
        if (! $this->image) {
            return '';
        }

        $disk = config('filesystems.default');

        return Storage::disk($disk)->url($this->image);
    }

    /**
     * Preço formatado em reais (converte centavos para reais)
     */
    public function getFormattedPriceAttribute(): string
    {
        return 'R$ '.number_format($this->price / 100, 2, ',', '.');
    }
}
