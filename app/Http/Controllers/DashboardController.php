<?php

namespace App\Http\Controllers;

use App\Http\Resources\ChartResource;
use Illuminate\Http\Request;
use App\Models\Order;

class DashboardController extends Controller
{
    public function chart()
    {
        $orders = Order::query()
            ->join('order_items', 'orders.id', '=', 'order_items.order_id')
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->selectRaw("DATE(orders.created_at) as date, SUM(order_items.quantity * products.price) as total")
            ->groupBy('date')
            ->orderBy('date', 'asc')
            ->get();

        return ChartResource::collection($orders);
    }
}
