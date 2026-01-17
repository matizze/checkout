<?php

namespace App\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;

class MoneyCast implements CastsAttributes
{
    /**
     * Cast the given value.
     *
     * @param  array<string, mixed>  $attributes
     */
    public function get(Model $model, string $key, mixed $value, array $attributes): string
    {
        return 'R$ '.number_format($value / 100, 2, ',', '.');
    }

    /**
     * Prepare the given value for storage.
     *
     * @param  array<string, mixed>  $attributes
     */
    public function set(Model $model, string $key, mixed $value, array $attributes): int
    {
        if (is_string($value)) {
            $cleaned = preg_replace('/[^\d.,]/', '', $value);

            if (str_contains($cleaned, ',')) {
                $decimal = (float) str_replace(',', '.', str_replace('.', '', $cleaned));

                return (int) round($decimal * 100);
            }

            return (int) round(((float) $cleaned) * 100);
        }

        return (int) $value;
    }
}
