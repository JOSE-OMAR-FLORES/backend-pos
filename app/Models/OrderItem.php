<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory;

    // Asegúrate de que estos campos existan en tu migración de la tabla 'order_items'
    protected $fillable = [
        'order_id',
        'product_id',
        'quantity',
        'price_at_order', // Nombre de columna para el precio en el momento de la compra
        // 'modifications', // Si tienes un campo JSON para modificaciones
    ];

    /**
     * Get the order that owns the order item.
     */
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * Get the product associated with the order item.
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}