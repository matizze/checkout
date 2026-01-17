<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreditCardRequest;
use App\Mail\ProductPurchaseMail;
use App\Models\Customer;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Models\Product;
use App\Rules\CpfRule;
use App\Services\AsaasService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
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
            'cpf_cnpj' => ['required', 'string', new CpfRule],
            'phone' => 'nullable|string|max:20',
        ]);

        // Remove formatacao do CPF/CNPJ
        $validated['cpf_cnpj'] = preg_replace('/\D/', '', $validated['cpf_cnpj']);

        session(['checkout.customer' => $validated]);

        return redirect()->route('checkout.payment-method');
    }

    /**
     * Step 3: Exibir opcoes de metodo de pagamento.
     */
    public function paymentMethod(): View|RedirectResponse
    {
        $productId = session('checkout.product_id');
        $customerData = session('checkout.customer');

        if (! $productId || ! $customerData) {
            return redirect()->route('products.index')
                ->with('error', 'Sessao expirada. Inicie o checkout novamente.');
        }

        $product = Product::findOrFail($productId);

        return view('checkout.payment-method', compact('product'));
    }

    /**
     * Step 3: Salvar metodo de pagamento escolhido.
     */
    public function storePaymentMethod(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'payment_method' => 'required|in:pix,credit_card',
        ]);

        session(['checkout.payment_method' => $validated['payment_method']]);

        if ($validated['payment_method'] === 'credit_card') {
            return redirect()->route('checkout.credit-card');
        }

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

        if ($product->price < 500) {
            return back()
                ->with('error', 'Valor mínimo para pagamento é R$ 5,00')
                ->withInput();
        }

        // Criar cobranca PIX no Asaas
        $dueDate = now()->addDays(1)->format('Y-m-d');

        try {
            $asaasPayment = $asaas->createPixPayment(
                $asaasCustomer['id'],
                (float) ($product->price / 100),
                $dueDate,
                "Pedido #{$order->id} - {$product->name}"
            );

            // Obter QR Code PIX
            $pixQrCode = $asaas->getPixQrCode($asaasPayment['id']);
        } catch (\Illuminate\Http\Client\RequestException $e) {
            \Log::error('Asaas API Error:', [
                'error' => $e->getMessage(),
                'response' => $e->response->json(),
                'order_id' => $order->id,
                'customer_id' => $asaasCustomer['id'] ?? 'N/A',
                'amount' => $product->price,
            ]);

            return back()
                ->with('error', 'Erro ao processar pagamento. Tente novamente.')
                ->withInput();
        }

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
     * Exibir formulario de cartao de credito.
     */
    public function creditCard(): View|RedirectResponse
    {
        $productId = session('checkout.product_id');
        $customerData = session('checkout.customer');

        if (! $productId || ! $customerData) {
            return redirect()->route('products.index')
                ->with('error', 'Sessao expirada. Inicie o checkout novamente.');
        }

        $product = Product::findOrFail($productId);

        return view('checkout.credit-card', compact('product'));
    }

    /**
     * Exibir pagina de erro de pagamento com cartao.
     */
    public function creditCardError(): View|RedirectResponse
    {
        $productId = session('checkout.product_id');
        $error = session('error');

        if (! $productId || ! $error) {
            return redirect()->route('products.index');
        }

        $product = Product::findOrFail($productId);

        return view('checkout.credit-card-error', compact('product', 'error'));
    }

    /**
     * Processar pagamento com cartao de credito.
     */
    public function processCreditCard(CreditCardRequest $request, AsaasService $asaas): RedirectResponse
    {
        $productId = session('checkout.product_id');
        $customerData = session('checkout.customer');

        if (! $productId || ! $customerData) {
            return redirect()->route('products.index')
                ->with('error', 'Sessao expirada. Inicie o checkout novamente.');
        }

        $product = Product::findOrFail($productId);

        if ($product->price < 500) {
            return back()
                ->with('error', 'Valor minimo para pagamento e R$ 5,00')
                ->withInput();
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

        // Criar cobranca com cartao de credito no Asaas
        $dueDate = now()->format('Y-m-d');

        try {
            $asaasPayment = $asaas->createCreditCardPayment(
                $asaasCustomer['id'],
                (float) ($product->price / 100),
                $dueDate,
                [
                    'holder_name' => $request->card_holder_name,
                    'number' => $request->card_number,
                    'expiry_month' => $request->card_expiry_month,
                    'expiry_year' => $request->card_expiry_year,
                    'cvv' => $request->card_cvv,
                ],
                [
                    'name' => $customer->name,
                    'email' => $customer->email,
                    'cpf_cnpj' => $customer->cpf_cnpj,
                    'postal_code' => $customer->postal_code,
                    'address_number' => $customer->address_number,
                    'phone' => $customer->phone,
                ],
                "Pedido #{$order->id} - {$product->name}"
            );
        } catch (\Illuminate\Http\Client\RequestException $e) {
            $errorMessage = 'Erro ao processar pagamento. Tente novamente.';
            $responseData = $e->response->json();

            \Log::error('Asaas Credit Card API Error:', [
                'error' => $e->getMessage(),
                'response' => $responseData,
                'order_id' => $order->id,
                'customer_id' => $asaasCustomer['id'] ?? 'N/A',
                'amount' => $product->price,
            ]);

            // Verificar erros especificos do cartao
            if (isset($responseData['errors'])) {
                foreach ($responseData['errors'] as $error) {
                    if (str_contains($error['description'] ?? '', 'cartao')) {
                        $errorMessage = $error['description'];
                        break;
                    }
                }
            }

            session(['error' => $errorMessage]);

            return redirect()->route('checkout.credit-card.error');
        }

        // Criar pagamento no banco local
        $payment = Payment::create([
            'order_id' => $order->id,
            'asaas_id' => $asaasPayment['id'],
            'billing_type' => 'CREDIT_CARD',
            'amount' => $product->price,
            'status' => $asaasPayment['status'],
            'due_date' => $dueDate,
        ]);

        // Se o pagamento foi aprovado imediatamente
        if (in_array($asaasPayment['status'], ['RECEIVED', 'CONFIRMED'])) {
            $payment->update(['paid_at' => now()]);
            $order->update(['status' => 'paid']);
            $this->sendPurchaseConfirmationEmail($payment);
        }

        session(['checkout.payment_id' => $payment->id]);

        // Limpar dados sensíveis da sessão
        session()->forget(['checkout.customer', 'checkout.payment_method']);

        return redirect()->route('checkout.status', $payment);
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
                $wasPending = $payment->paid_at === null;

                $payment->update([
                    'status' => $asaasPayment['status'],
                    'paid_at' => in_array($asaasPayment['status'], ['RECEIVED', 'CONFIRMED'])
                        ? now()
                        : null,
                ]);

                // Atualizar status do pedido e enviar email se pagamento confirmado
                if (in_array($asaasPayment['status'], ['RECEIVED', 'CONFIRMED'])) {
                    $payment->order->update(['status' => 'paid']);

                    if ($wasPending) {
                        $this->sendPurchaseConfirmationEmail($payment);
                    }
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
                $wasPending = $payment->paid_at === null;

                $payment->update([
                    'status' => $asaasPayment['status'],
                    'paid_at' => in_array($asaasPayment['status'], ['RECEIVED', 'CONFIRMED'])
                        ? now()
                        : null,
                ]);

                if (in_array($asaasPayment['status'], ['RECEIVED', 'CONFIRMED'])) {
                    $payment->order->update(['status' => 'paid']);

                    if ($wasPending) {
                        $this->sendPurchaseConfirmationEmail($payment);
                    }
                }
            }
        }

        return [
            'status' => $payment->status,
            'paid' => in_array($payment->status, ['RECEIVED', 'CONFIRMED']),
        ];
    }

    /**
     * Enviar email de confirmação de compra.
     */
    private function sendPurchaseConfirmationEmail(Payment $payment): void
    {
        $payment->load('order.customer', 'order.items.product');

        $order = $payment->order;
        $customer = $order->customer;
        $product = $order->items->first()?->product;

        if ($customer && $product) {
            Mail::to($customer->email)->send(
                new ProductPurchaseMail($product, $customer, $order)
            );
        }
    }
}
