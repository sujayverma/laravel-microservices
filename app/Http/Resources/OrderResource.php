<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\OrderItemResource;

class OrderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $totalNumberOfOrder = $this->orderItems->count();
        return [
            'id' => $this->id,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'email' => $this->email,
            'phone' => $this->phone,
            'order_items' => OrderItemResource::collection($this->orderItems),
            'total_items' => $totalNumberOfOrder,
            'final_price' => $this->orderItems->reduce(function ($carry, $item) {
                $product = new ProductResource($item->product);
                return round($carry + ($product->price * $item->quantity), 2);
            }, 0)
        ];
    }
}
