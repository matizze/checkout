<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class CpfRule implements ValidationRule
{
    /**
     * Run the validation rule.
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        // Remove non-digit characters
        $cpf = preg_replace('/\D/', '', $value);

        // Check if it's exactly 11 digits
        if (\strlen($cpf) !== 11) {
            $fail('O CPF deve ter 11 dígitos.');

            return;
        }

        // Check for repeated digits (invalid CPFs)
        if (preg_match('/(\d)\1{10}/', $cpf)) {
            $fail('CPF inválido.');

            return;
        }

        // Calculate first check digit
        $sum = 0;
        for ($i = 0; $i < 9; $i++) {
            $sum += \intval($cpf[$i]) * (10 - $i);
        }
        $remainder = $sum % 11;
        $firstCheckDigit = $remainder < 2 ? 0 : 11 - $remainder;

        // Check first check digit
        if (\intval($cpf[9]) !== $firstCheckDigit) {
            $fail('CPF inválido.');

            return;
        }

        // Calculate second check digit
        $sum = 0;
        for ($i = 0; $i < 10; $i++) {
            $sum += \intval($cpf[$i]) * (11 - $i);
        }
        $remainder = $sum % 11;
        $secondCheckDigit = $remainder < 2 ? 0 : 11 - $remainder;

        // Check second check digit
        if (\intval($cpf[10]) !== $secondCheckDigit) {
            $fail('CPF inválido.');

            return;
        }
    }
}
