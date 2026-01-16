# Checkout com Asaas

## Resumo dos passos executados:

1.  **Cliente criado:**
    - ID: `cus_000156950328`
    - Nome: Cliente Teste PIX
    - CPF: `24971563792`

2.  **Cobrança PIX criada:**
    - ID: `pay_4zx48vneo4biu3pk`
    - Valor: R$ 100,00
    - Vencimento: 2026-01-20
    - Status: `PENDING`
    - Fatura: [https://www.asaas.com/i/4zx48vneo4biu3pk](https://www.asaas.com/i/4zx48vneo4biu3pk)

3.  **QR Code PIX gerado:**
    - Payload PIX (copiar e colar):
        ```bash
        00020101021226800014br.gov.bcb.pix2558pix.asaas.com/qr/cobv/719637bd-903f-4ef4-9227-7a8d77b6d0835204000053039865802BR5925MATIZZE CONSULTORIA E SOL6012Porto Alegre61089056000162070503***63048305
        ```
    - Expiração: `2027-01-20 23:59:59`
    - Imagem QR Code: Base64 fornecido (pode ser usado em `<img src="data:image/png;base64,...">`)

## Comandos `curl` completos usados:

```bash
# 1. Criar cliente

curl -X POST https://www.asaas.com/api/v3/customers \
 -H "Content-Type: application/json" \
 -H "access_token: $ASAAS_TOKEN" \
 -d '{"name": "Cliente Teste PIX", "cpfCnpj": "24971563792", "email": "cliente.pix@teste.com"}'

# 2. Criar cobrança PIX

curl -X POST https://www.asaas.com/api/v3/payments \
 -H "Content-Type: application/json" \
 -H "access_token: $ASAAS_TOKEN" \
 -d '{"customer": "cus_000156950328", "billingType": "PIX", "value": 100.00, "dueDate": "2026-01-20", "description": "Pedido #1234"}'

# 3. Obter QR Code

curl -X GET https://www.asaas.com/api/v3/payments/pay_4zx48vneo4biu3pk/pixQrCode \
 -H "accept: application/json" \
 -H "access_token: $ASAAS_TOKEN"
```

_Nota:_ A URL base correta é `https://www.asaas.com/api/v3/` (não `https://api.asaas.com`).
# checkout
