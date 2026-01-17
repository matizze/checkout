<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Handle Credit Card Payment Request
 *
 * @property-read string $card_number
 * @property-read string $card_holder_name
 * @property-read string $card_expiry_month
 * @property-read string $card_expiry_year
 * @property-read string $card_cvv
 */
class CreditCardRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $currentYear = (int) date('Y');

        return [
            'card_number' => ['required', 'string', 'min:16', 'max:19'],
            'card_holder_name' => ['required', 'string', 'max:255'],
            'card_expiry_month' => ['required', 'string', 'size:2', 'regex:/^(0[1-9]|1[0-2])$/'],
            'card_expiry_year' => [
                'required',
                'string',
                'size:4',
                'regex:/^\d{4}$/',
                function ($attribute, $value, $fail) use ($currentYear) {
                    if ((int) $value < $currentYear) {
                        $fail('O cartao esta expirado.');
                    }
                },
            ],
            'card_cvv' => ['required', 'string', 'min:3', 'max:4'],
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'card_number.required' => 'O numero do cartao e obrigatorio.',
            'card_number.min' => 'O numero do cartao deve ter pelo menos 16 digitos.',
            'card_holder_name.required' => 'O nome do titular e obrigatorio.',
            'card_expiry_month.required' => 'O mes de validade e obrigatorio.',
            'card_expiry_month.regex' => 'O mes de validade deve estar entre 01 e 12.',
            'card_expiry_year.required' => 'O ano de validade e obrigatorio.',
            'card_expiry_year.min' => 'O cartao esta expirado.',
            'card_cvv.required' => 'O codigo de seguranca (CVV) e obrigatorio.',
            'card_cvv.min' => 'O CVV deve ter pelo menos 3 digitos.',
            'card_cvv.max' => 'O CVV deve ter no maximo 4 digitos.',
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        if ($this->card_number) {
            $this->merge([
                'card_number' => preg_replace('/\s+/', '', $this->card_number),
            ]);
        }
    }
}
