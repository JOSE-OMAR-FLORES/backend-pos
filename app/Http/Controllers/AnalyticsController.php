<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Models\Order;
use Carbon\Carbon;

class AnalyticsController extends Controller
{
    public function dashboard()
    {
        $today = Carbon::today();

        $ventasHoy = Order::whereDate('created_at', $today)->sum('total_amount');
        $ordenesHoy = Order::whereDate('created_at', $today)->count();
        $ordenesTotales = Order::count();
        $clientesUnicos = Order::distinct('customer_name')->count('customer_name');
        $tiempoPromedio = Order::whereNotNull('estimated_time')->avg('estimated_time');

        // 📊 Ventas semanales (gráfico de barras)
    $ventasSemanales = Order::selectRaw("TO_CHAR(created_at, 'Day') as dia, SUM(total_amount) as total")
            ->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])
            ->groupBy('dia')
            ->get();

        // 📊 Productos más vendidos (gráfico de pastel) - Incluye categorías aunque no tengan ventas
        $categoriasFijas = [
            'hamburguesas' => 0,
            'pizzas'       => 0,
            'postres'      => 0,
            'bebidas'      => 0
        ];

        $productosVendidosDB = DB::table('order_items')
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->whereBetween('orders.created_at', [now()->startOfWeek(), now()->endOfWeek()])
            ->select(
                'products.category as categoria',
                DB::raw('COALESCE(SUM(order_items.quantity), 0) as cantidad')
            )
            ->groupBy('products.category')
            ->pluck('cantidad', 'categoria')
            ->toArray();

        // Mezcla datos de la BD con las categorías fijas
        foreach ($productosVendidosDB as $categoria => $cantidad) {
            if (isset($categoriasFijas[$categoria])) {
                $categoriasFijas[$categoria] = (int) $cantidad;
            }
        }

        // Convierte a colección de objetos para JSON
        $productosMasVendidos = collect($categoriasFijas)->map(function ($cantidad, $categoria) {
            return [
                'categoria' => $categoria,
                'cantidad'  => $cantidad
            ];
        })->values();

        return response()->json([
            'ventas_hoy'            => $ventasHoy,
            'ordenes_hoy'           => $ordenesHoy,
            'ordenes_totales'       => $ordenesTotales,
            'clientes_unicos'       => $clientesUnicos,
            'tiempo_promedio'       => round($tiempoPromedio, 2),
            'ventas_semanales'      => $ventasSemanales,
            'productos_mas_vendidos'=> $productosMasVendidos
        ]);
    }

    public function monthlySummary()
    {
        $month = now()->format('Y-m');

        $monthlyOrders = Order::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();

        $monthlySales = Order::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->sum('total_amount');

        $summaryText = "Resumen mensual $month:\n";
        $summaryText .= "Órdenes: $monthlyOrders\n";
        $summaryText .= "Ventas: $" . number_format($monthlySales, 2) . "\n";

        Storage::put("analytics/monthly_report_$month.txt", $summaryText);

        return response($summaryText, 200)
            ->header('Content-Type', 'text/plain');
    }
}
