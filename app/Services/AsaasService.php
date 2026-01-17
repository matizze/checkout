<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class AsaasService
{
    /**
     * Criar um novo cliente no Asaas.
     *
     * @param  array  $data  [name, cpfCnpj, email?, phone?, address?, addressNumber?, province?, postalCode?]
     */
    public function createCustomer(array $data): array
    {
        return Http::asaas()
            ->post('/customers', $data)
            ->throw()
            ->json();
    }

    /**
     * Buscar um cliente pelo ID do Asaas.
     */
    public function getCustomer(string $asaasId): array
    {
        return Http::asaas()
            ->get("/customers/{$asaasId}")
            ->throw()
            ->json();
    }

    /**
     * Atualizar um cliente existente.
     */
    public function updateCustomer(string $asaasId, array $data): array
    {
        return Http::asaas()
            ->put("/customers/{$asaasId}", $data)
            ->throw()
            ->json();
    }

    /**
     * Criar uma nova cobranca no Asaas.
     *
     * @param  array  $data  [customer, billingType, value, dueDate, description?]
     */
    public function createPayment(array $data): array
    {
        // Log para debug
        \Log::info('Asaas API - Creating payment with data:', $data);

        $response = Http::asaas()
            ->post('/payments', $data)
            ->throw()
            ->json();

        \Log::info('Asaas API - Payment created:', $response);

        return $response;
    }

    /**
     * Buscar uma cobranca pelo ID do Asaas.
     */
    public function getPayment(string $asaasId): array
    {
        return Http::asaas()
            ->get("/payments/{$asaasId}")
            ->throw()
            ->json();
    }

    /**
     * Obter QR Code PIX de uma cobranca.
     *
     * @return array [encodedImage, payload, expirationDate]
     */
    public function getPixQrCode(string $paymentId): array
    {
        return Http::asaas()
            ->get("/payments/{$paymentId}/pixQrCode")
            ->throw()
            ->json();
    }

    /**
     * Criar cobranca PIX com dados simplificados.
     *
     * @param  string  $customerId  ID do cliente no Asaas
     * @param  float  $value  Valor em reais
     * @param  string  $dueDate  Data de vencimento (YYYY-MM-DD)
     * @param  string|null  $description  Descricao da cobranca
     */
    public function createPixPayment(
        string $customerId,
        float $value,
        string $dueDate,
        ?string $description = null
    ): array {
        // Validação dos dados antes de enviar para API
        if (empty($customerId)) {
            throw new \InvalidArgumentException('Customer ID is required');
        }

        if ($value <= 0) {
            throw new \InvalidArgumentException('Value must be greater than 0');
        }

        $data = [
            'customer' => $customerId,
            'billingType' => 'PIX',
            'value' => round($value, 2), // Garante 2 casas decimais
            'dueDate' => $dueDate,
            'daysAfterDueDate' => 1, // Campo obrigatório pela API
        ];

        if ($description) {
            $data['description'] = substr($description, 0, 255); // Limita tamanho
        }

        return $this->createPayment($data);
    }

    /**
     * Criar cobranca com cartao de credito.
     *
     * @param  string  $customerId  ID do cliente no Asaas
     * @param  float  $value  Valor em reais
     * @param  string  $dueDate  Data de vencimento (YYYY-MM-DD)
     * @param  array  $creditCard  Dados do cartao [holder_name, number, expiry_month, expiry_year, cvv]
     * @param  array  $holderInfo  Dados do titular [name, email, cpf_cnpj, postal_code, address_number, phone]
     * @param  string|null  $description  Descricao da cobranca
     */
    public function createCreditCardPayment(
        string $customerId,
        float $value,
        string $dueDate,
        array $creditCard,
        array $holderInfo,
        ?string $description = null
    ): array {
        if (empty($customerId)) {
            throw new \InvalidArgumentException('Customer ID is required');
        }

        if ($value <= 0) {
            throw new \InvalidArgumentException('Value must be greater than 0');
        }

        $data = [
            'customer' => $customerId,
            'billingType' => 'CREDIT_CARD',
            'value' => round($value, 2),
            'dueDate' => $dueDate,
            'creditCard' => [
                'holderName' => $creditCard['holder_name'],
                'number' => preg_replace('/\D/', '', $creditCard['number']),
                'expiryMonth' => $creditCard['expiry_month'],
                'expiryYear' => $creditCard['expiry_year'],
                'ccv' => $creditCard['cvv'],
            ],
            'creditCardHolderInfo' => [
                'name' => $holderInfo['name'],
                'email' => $holderInfo['email'],
                'cpfCnpj' => preg_replace('/\D/', '', $holderInfo['cpf_cnpj']),
                'postalCode' => preg_replace('/\D/', '', $holderInfo['postal_code'] ?? '00000000'),
                'addressNumber' => $holderInfo['address_number'] ?? '0',
                'phone' => preg_replace('/\D/', '', $holderInfo['phone'] ?? ''),
            ],
        ];

        if ($description) {
            $data['description'] = substr($description, 0, 255);
        }

        return $this->createPayment($data);
    }
}
