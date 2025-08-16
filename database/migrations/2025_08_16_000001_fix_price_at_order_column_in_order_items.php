<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Si no existe la columna, la agregamos con default 0
        if (!Schema::hasColumn('order_items', 'price_at_order')) {
            Schema::table('order_items', function (Blueprint $table) {
                $table->decimal('price_at_order', 10, 2)->after('quantity')->default(0);
            });
        } else {
            // Si existe pero no tiene default, se lo ponemos
            DB::statement("ALTER TABLE order_items ALTER COLUMN price_at_order SET DEFAULT 0");
            // Si es nullable, lo ponemos NOT NULL
            DB::statement("ALTER TABLE order_items ALTER COLUMN price_at_order SET NOT NULL");
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('order_items', 'price_at_order')) {
            Schema::table('order_items', function (Blueprint $table) {
                $table->dropColumn('price_at_order');
            });
        }
    }
};
