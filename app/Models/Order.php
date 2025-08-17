<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    // Asegúrate de que estos campos existan en tu migración de la tabla 'orders'
    protected $fillable = [
        'status',
        'total', // Nombre de columna para el total
        'payment_method', // Nueva columna
        'customer_name', // Nueva columna (opcional)
        'notes', // Nueva columna (opcional)
        'estimated_time', // Nueva columna (opcional)
        'priority', // Nueva columna (opcional)
        // 'user_id', // Si asocias pedidos a un usuario/cajero
    ];

    /**
     * Get the order items for the order.
     */
    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }
}