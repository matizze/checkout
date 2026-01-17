<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->cascadeOnDelete();
            $table->string('asaas_id')->unique()->nullable();
            $table->string('billing_type')->default('PIX'); // PIX, CREDIT_CARD, BOLETO
            $table->tinyInteger('installments')->default(1);
            $table->integer('amount'); // Valor em centavos
            $table->string('status')->default('PENDING');
            $table->date('due_date');
            $table->text('pix_payload')->nullable();
            $table->text('pix_qrcode_base64')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
