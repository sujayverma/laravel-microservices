<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\ProductResource;

class OrderItemResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $product = new ProductResource($this->product);
        return [
            'order_item_id' => $this->id,
            'order_id' => $this->order_id,
            'product' => $product,
            'quantity' => $this->quantity,
            'product_price' => $product->price,
            'total_price' => round($product->price * $this->quantity, 2),
            'ordered_at' => $this->created_at
        ];
    }
}
