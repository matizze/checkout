<?php

namespace App\Mail;

use App\Models\Customer;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;

class ProductPurchaseMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public function __construct(
        public Product $product,
        public Customer $customer,
        public Order $order
    ) {
        $this->onQueue('emails');
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: "Compra Confirmada - {$this->product->name}",
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.product-purchase',
        );
    }

    public function attachments(): array
    {
        $attachments = [];

        if ($this->product->attachment) {
            $disk = config('filesystems.default');

            if (Storage::disk($disk)->exists($this->product->attachment)) {
                $path = Storage::disk($disk)->path($this->product->attachment);

                $attachments[] = Attachment::fromPath($path)
                    ->as(basename($this->product->attachment))
                    ->withMime(mime_content_type($path));
            }
        }

        return $attachments;
    }

    public function getProcessedMessage(): string
    {
        return "Olá {$this->customer->name},\n\n".
               "Sua compra do produto {$this->product->name} foi confirmada!\n".
               "Pedido: #{$this->order->id}\n".
               'Total: R$ '.number_format($this->order->total_amount / 100, 2, ',', '.')."\n\n".
               'Obrigado pela compra!';
    }
}
