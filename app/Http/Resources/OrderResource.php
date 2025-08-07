<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
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
            'order_number' => $this->id, // O si tienes una columna 'order_number' en tu tabla orders
            'total_amount' => (float) $this->total_amount, // Asegura que sea un flotante
            'status' => $this->status,
            'payment_method' => $this->payment_method, // Asegúrate de que esta columna exista
            'customer_name' => $this->customer_name, // Asegúrate de que esta columna exista
            'notes' => $this->notes, // Asegúrate de que esta columna exista
            'estimated_time' => $this->estimated_time ?? 15, // Si tienes esta columna, sino pon un default
            'priority' => $this->priority ?? 'normal', // Si tienes esta columna, sino pon un default
            'created_at' => $this->created_at, // Carbon instance, se serializará a ISO string
            'updated_at' => $this->updated_at,
            'items' => OrderItemResource::collection($this->whenLoaded('items')),
        ];
    }
}