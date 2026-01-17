<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Converte valores existentes para centavos
        DB::statement('UPDATE products SET price = ROUND(price * 100)');

        Schema::table('products', function (Blueprint $table) {
            $table->integer('price')->change(); // Preço em centavos
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->decimal('price', 10, 2)->change();
        });

        // Converte de volta para reais
        DB::statement('UPDATE products SET price = price / 100.0');
    }
};
