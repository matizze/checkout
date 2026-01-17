<?php

namespace App\Models;

use App\Casts\MoneyCast;
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

    protected function casts(): array
    {
        return [
            'price' => MoneyCast::class,
        ];
    }

    public function getImageUrlAttribute(): string
    {
        if (! $this->image) {
            return '';
        }

        $disk = config('filesystems.default');

        return Storage::disk($disk)->url($this->image);
    }
}
