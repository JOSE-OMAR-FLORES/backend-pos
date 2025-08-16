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
            // Verificamos si la columna no existe antes de agregarla
            if (!Schema::hasColumn('order_items', 'price_at_order')) {
                $table->decimal('price_at_order', 10, 2)->after('quantity')->default(0);
            }
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