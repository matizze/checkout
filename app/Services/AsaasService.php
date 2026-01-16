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
        return Http::asaas()
            ->post('/payments', $data)
            ->throw()
            ->json();
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
        $data = [
            'customer' => $customerId,
            'billingType' => 'PIX',
            'value' => $value,
            'dueDate' => $dueDate,
        ];

        if ($description) {
            $data['description'] = $description;
        }

        return $this->createPayment($data);
    }
}
