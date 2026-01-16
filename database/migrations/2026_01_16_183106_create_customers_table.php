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
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->string('asaas_id')->unique()->nullable();
            $table->string('name');
            $table->string('email')->nullable();
            $table->string('cpf_cnpj', 14);
            $table->string('phone', 20)->nullable();
            $table->string('postal_code', 9)->nullable();
            $table->string('address')->nullable();
            $table->string('address_number', 10)->nullable();
            $table->string('province')->nullable();
            $table->string('city')->nullable();
            $table->string('state', 2)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};
