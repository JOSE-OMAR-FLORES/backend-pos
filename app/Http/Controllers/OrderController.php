<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Resources\OrderResource; // Importa el OrderResource
use App\Models\Product; // Importa el modelo Product
use Illuminate\Support\Facades\Log; // Importa el facade Log para registrar errores

class OrderController extends Controller
{
    /**
     * Muestra una lista de pedidos.
     * Permite filtrar por estado (para KitchenDisplay).
     */
    public function index(Request $request)
    {
        $query = Order::with('items.product');

        // Filtro por estado(s)
        if ($request->has('status')) {
            // Divide la cadena de estados por coma (ej. "pending,preparing")
            $statuses = explode(',', $request->input('status'));
            $query->whereIn('status', $statuses);
        }

        // Puedes añadir lógica de ordenamiento aquí si lo deseas.
        // Ejemplo: Ordenar por los pedidos más antiguos primero (los que llevan más tiempo)
        $query->orderBy('created_at', 'asc');
        // O si tienes una columna 'priority' en orders:
        // $query->orderByRaw("FIELD(priority, 'urgent') DESC")->orderBy('created_at', 'asc');


        $orders = $query->get();

        // Usamos OrderResource::collection para formatear una colección de pedidos
        return OrderResource::collection($orders);
    }

    /**
     * Muestra un pedido específico.
     */
    public function show(Order $order)
    {
        // Usamos un OrderResource para formatear un solo pedido
        return new OrderResource($order->load('items.product'));
    }

    /**
     * Almacena un nuevo pedido.
     */
    public function store(Request $request)
{
    try {
        $request->validate([
            'items' => 'required|array',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.price_at_order' => 'nullable|numeric|min:0',
            'payment_method' => 'required|string|max:50',
        ]);

        DB::beginTransaction();

        // Calcular el total sumando los subtotales de cada item
        $total = 0;
        foreach ($request->items as $item) {
            $product = Product::findOrFail($item['product_id']);
            $price = isset($item['price_at_order']) ? $item['price_at_order'] : $product->price;
            $subtotal = $price * $item['quantity'];
            $total += $subtotal;
        }

        $order = Order::create([
            'status' => 'pending',
            'total_amount' => $total,
            'payment_method' => $request->payment_method,
            'customer_name' => $request->customer_name ?? null,
            'notes' => $request->notes ?? null,
            'estimated_time' => $request->estimated_time ?? null,
            'priority' => $request->priority ?? 'normal',
        ]);

        foreach ($request->items as $item) {
            $product = Product::findOrFail($item['product_id']);
            $price = isset($item['price_at_order']) ? $item['price_at_order'] : $product->price;
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $product->id,
                'quantity' => $item['quantity'],
                'price_at_order' => $price,
            ]);
        }

        DB::commit();

        return response()->json([
            'message' => 'Order created successfully',
            'order' => $order->load('items.product'),
        ], 201);

    } catch (\Exception $e) {
        DB::rollBack();
        \Log::error('Error creating order: ' . $e->getMessage());
        return response()->json([
            'error' => 'Error creating order',
            'message' => $e->getMessage(),
        ], 500);
    }
}


    /**
     * Actualiza un pedido existente.
     * Este método se usará para cambiar el estado del pedido.
     */
    public function update(Request $request, Order $order)
    {
        $request->validate([
            // Valida que el 'status' enviado sea uno de los permitidos por el frontend.
            // Asegúrate de que estos estados estén definidos en tu migración como ENUM o validados de forma similar.
            'status' => 'required|in:pending,preparing,ready,delivered,cancelled',
        ]);

        $order->update([
            'status' => $request->status
            // Puedes añadir más campos a actualizar si es necesario, por ejemplo:
            // 'notes' => $request->notes,
        ]);

        // Devuelve el pedido actualizado con sus relaciones usando el OrderResource
        return new OrderResource($order->load('items.product'));
    }

    /**
     * Elimina un pedido.
     * (Normalmente no se usa en sistemas POS por razones de historial/contabilidad,
     * pero se incluye por completitud de Route::apiResource).
     */
    public function destroy(Order $order)
    {
        // Se recomienda también eliminar los OrderItems asociados si se elimina el Order
        // Si tienes onDelete('cascade') en tu migración de order_items, no es necesario hacer esto explícitamente aquí.
        // $order->items()->delete();

        $order->delete();
        return response()->json(null, 204); // 204 No Content
    }
}