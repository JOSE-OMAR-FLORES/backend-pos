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
        Schema::table('order_items', function (Blueprint $table) {
            // Renombrar 'price' a 'price_at_order' si ya existe
            // $table->renameColumn('price', 'price_at_order'); // Requiere doctrine/dbal

            // Si 'price_at_order' no existe, añadirla
            $table->decimal('price_at_order', 10, 2)->after('quantity')->default(0);

            // Si quieres guardar modificaciones como JSON
            // $table->json('modifications')->nullable()->after('price_at_order');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('order_items', function (Blueprint $table) {
            // Revertir en orden inverso
            // $table->dropColumn('modifications');
            $table->dropColumn('price_at_order'); // O renombrar de vuelta
            // Si renombraste: $table->renameColumn('price_at_order', 'price');
        });
    }
};