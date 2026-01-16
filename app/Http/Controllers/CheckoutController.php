<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Models\Product;
use App\Services\AsaasService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CheckoutController extends Controller
{
    /**
     * Step 1: Exibir produto selecionado (carrinho).
     */
    public function start(Product $product): View
    {
        session(['checkout.product_id' => $product->id]);

        return view('checkout.start', compact('product'));
    }

    /**
     * Step 2: Exibir formulario de dados do cliente.
     */
    public function customer(): View
    {
        $productId = session('checkout.product_id');

        if (! $productId) {
            abort(404, 'Produto nao encontrado na sessao.');
        }

        $product = Product::findOrFail($productId);
        $customerData = session('checkout.customer', []);

        return view('checkout.customer', compact('product', 'customerData'));
    }

    /**
     * Step 2: Salvar dados do cliente na sessao.
     */
    public function storeCustomer(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'cpf_cnpj' => 'required|string|min:11|max:14',
            'phone' => 'nullable|string|max:20',
        ]);

        // Remove formatacao do CPF/CNPJ
        $validated['cpf_cnpj'] = preg_replace('/\D/', '', $validated['cpf_cnpj']);

        session(['checkout.customer' => $validated]);

        return redirect()->route('checkout.payment');
    }

    /**
     * Step 3: Processar pagamento e exibir QR Code PIX.
     */
    public function payment(AsaasService $asaas): View|RedirectResponse
    {
        $productId = session('checkout.product_id');
        $customerData = session('checkout.customer');

        if (! $productId || ! $customerData) {
            return redirect()->route('products.index')
                ->with('error', 'Sessao expirada. Inicie o checkout novamente.');
        }

        $product = Product::findOrFail($productId);

        // Verificar se ja existe um pagamento pendente na sessao
        $paymentId = session('checkout.payment_id');

        if ($paymentId) {
            $payment = Payment::find($paymentId);

            if ($payment && $payment->status === 'PENDING') {
                return view('checkout.payment', compact('product', 'payment'));
            }
        }

        // Criar cliente no banco local
        $customer = Customer::create($customerData);

        // Criar cliente no Asaas
        $asaasCustomer = $asaas->createCustomer([
            'name' => $customer->name,
            'cpfCnpj' => $customer->cpf_cnpj,
            'email' => $customer->email,
            'phone' => $customer->phone,
        ]);

        $customer->update(['asaas_id' => $asaasCustomer['id']]);

        // Criar pedido
        $order = Order::create([
            'customer_id' => $customer->id,
            'total_amount' => $product->price,
            'status' => 'pending',
        ]);

        // Criar item do pedido
        OrderItem::create([
            'order_id' => $order->id,
            'product_id' => $product->id,
            'quantity' => 1,
            'unit_price' => $product->price,
            'subtotal' => $product->price,
        ]);

        // Criar cobranca PIX no Asaas
        $dueDate = now()->addDays(1)->format('Y-m-d');

        $asaasPayment = $asaas->createPixPayment(
            $asaasCustomer['id'],
            (float) $product->price,
            $dueDate,
            "Pedido #{$order->id} - {$product->name}"
        );

        // Obter QR Code PIX
        $pixQrCode = $asaas->getPixQrCode($asaasPayment['id']);

        // Criar pagamento no banco local
        $payment = Payment::create([
            'order_id' => $order->id,
            'asaas_id' => $asaasPayment['id'],
            'billing_type' => 'PIX',
            'amount' => $product->price,
            'status' => $asaasPayment['status'],
            'due_date' => $dueDate,
            'pix_payload' => $pixQrCode['payload'],
            'pix_qrcode_base64' => $pixQrCode['encodedImage'],
        ]);

        session(['checkout.payment_id' => $payment->id]);

        return view('checkout.payment', compact('product', 'payment'));
    }

    /**
     * Exibir status do pagamento.
     */
    public function status(Payment $payment, AsaasService $asaas): View
    {
        // Atualizar status do pagamento via Asaas
        if ($payment->asaas_id && $payment->status === 'PENDING') {
            $asaasPayment = $asaas->getPayment($payment->asaas_id);

            if ($asaasPayment['status'] !== $payment->status) {
                $payment->update([
                    'status' => $asaasPayment['status'],
                    'paid_at' => in_array($asaasPayment['status'], ['RECEIVED', 'CONFIRMED'])
                        ? now()
                        : null,
                ]);

                // Atualizar status do pedido se pagamento confirmado
                if (in_array($asaasPayment['status'], ['RECEIVED', 'CONFIRMED'])) {
                    $payment->order->update(['status' => 'paid']);
                }
            }
        }

        $payment->load('order.customer', 'order.items.product');

        return view('checkout.status', compact('payment'));
    }

    /**
     * Verificar status do pagamento via AJAX.
     */
    public function checkStatus(Payment $payment, AsaasService $asaas): array
    {
        if ($payment->asaas_id && $payment->status === 'PENDING') {
            $asaasPayment = $asaas->getPayment($payment->asaas_id);

            if ($asaasPayment['status'] !== $payment->status) {
                $payment->update([
                    'status' => $asaasPayment['status'],
                    'paid_at' => in_array($asaasPayment['status'], ['RECEIVED', 'CONFIRMED'])
                        ? now()
                        : null,
                ]);

                if (in_array($asaasPayment['status'], ['RECEIVED', 'CONFIRMED'])) {
                    $payment->order->update(['status' => 'paid']);
                }
            }
        }

        return [
            'status' => $payment->status,
            'paid' => in_array($payment->status, ['RECEIVED', 'CONFIRMED']),
        ];
    }
}
