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
        Schema::table('orders', function (Blueprint $table) {
            // Asegúrate de que la columna total_amount no se duplique
            if (!Schema::hasColumn('orders', 'total_amount')) {
                // Solo si no existe, añade total_amount
                $table->decimal('total_amount', 10, 2)->after('status')->default(0);
            }

            if (!Schema::hasColumn('orders', 'payment_method')) {
                $table->string('payment_method')->after('total_amount')->default('cash');
            }

            if (!Schema::hasColumn('orders', 'customer_name')) {
                $table->string('customer_name')->nullable()->after('payment_method');
            }

            if (!Schema::hasColumn('orders', 'notes')) {
                $table->text('notes')->nullable()->after('customer_name');
            }

            if (!Schema::hasColumn('orders', 'estimated_time')) {
                $table->integer('estimated_time')->nullable()->after('notes');
            }

            if (!Schema::hasColumn('orders', 'priority')) {
                $table->string('priority')->default('normal')->after('estimated_time');
            }
        });

        Schema::table('order_items', function (Blueprint $table) {
            // Asegúrate de que la columna price_at_order no se duplique
            if (!Schema::hasColumn('order_items', 'price_at_order')) {
                $table->decimal('price_at_order', 10, 2)->after('quantity');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            if (Schema::hasColumn('orders', 'priority')) {
                $table->dropColumn('priority');
            }
            if (Schema::hasColumn('orders', 'estimated_time')) {
                $table->dropColumn('estimated_time');
            }
            if (Schema::hasColumn('orders', 'notes')) {
                $table->dropColumn('notes');
            }
            if (Schema::hasColumn('orders', 'customer_name')) {
                $table->dropColumn('customer_name');
            }
            if (Schema::hasColumn('orders', 'payment_method')) {
                $table->dropColumn('payment_method');
            }
            if (Schema::hasColumn('orders', 'total_amount')) {
                $table->dropColumn('total_amount');
            }
        });

        Schema::table('order_items', function (Blueprint $table) {
            if (Schema::hasColumn('order_items', 'price_at_order')) {
                $table->dropColumn('price_at_order');
            }
        });
    }
};
