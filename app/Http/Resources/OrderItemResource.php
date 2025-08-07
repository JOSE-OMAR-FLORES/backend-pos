<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderItemResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'order_id' => $this->order_id,
            'product_id' => $this->product_id,
            'product_name' => $this->whenLoaded('product', fn() => $this->product->name), // Carga el nombre del producto
            'quantity' => (int) $this->quantity,
            'price_at_order' => (float) $this->price_at_order, // Asegura que sea flotante
            // 'modifications' => $this->modifications ? json_decode($this->modifications) : [], // Si tienes esta columna
        ];
    }
}