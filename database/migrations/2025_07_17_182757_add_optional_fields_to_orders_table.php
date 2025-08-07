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
            // Renombrar 'total' a 'total_amount' si ya existe
            // $table->renameColumn('total', 'total_amount'); // Cuidado: esto requiere el paquete doctrine/dbal

            // Si 'total_amount' no existe, añadirla
            $table->decimal('total_amount', 10, 2)->after('status')->default(0);

            $table->string('payment_method')->after('total_amount')->default('cash');
            $table->string('customer_name')->nullable()->after('payment_method');
            $table->text('notes')->nullable()->after('customer_name');
            $table->integer('estimated_time')->nullable()->after('notes'); // Para el KDS
            $table->string('priority')->default('normal')->after('estimated_time'); // Para el KDS
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            // Revertir en orden inverso
            $table->dropColumn('priority');
            $table->dropColumn('estimated_time');
            $table->dropColumn('notes');
            $table->dropColumn('customer_name');
            $table->dropColumn('payment_method');
            $table->dropColumn('total_amount'); // O renombrar de vuelta si lo hiciste
            // Si renombraste: $table->renameColumn('total_amount', 'total');
        });
    }
};