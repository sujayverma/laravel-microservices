<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Http\Resources\OrderResource;

class OrderController extends Controller
{
    //

    public function index()
    {
        $orders = Order::paginate(10);
        return OrderResource::collection($orders);
    }

    public function show($id)
    {
        $order = Order::with('orderItems.product')->find($id);
        if (!$order) {
            return response()->json(['message' => 'Order not found'], 404);
        }
        return new OrderResource($order);
    }

    public function exportCsv()
    {
        $orders = Order::with('orderItems.product')->get();

        $filename = "orders_export_" . date('Ymd_His') . ".csv";
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        $callback = function() use ($orders){
            $file = fopen('php://output', 'w');
            fputcsv($file, ['Order ID', 'Name', 'Email', 'Pnone', 'Product Name', 'Quantity', 'Product Price', 'Total Price', 'Ordered At']);

            foreach ($orders as $order) {
                foreach($order->orderItems as $item){
                    $product = $item->product;
                    $totalPrice = round($product->price * $item->quantity, 2);
                    fputcsv($file, [
                        $order->id,
                        $order->full_name,
                        $order->email,
                        $order->phone,
                        $product->name,
                        $item->quantity,
                        $product->price,
                        $totalPrice,
                        $item->created_at
                    ]);
                }
            }
        };

        return response()->stream($callback, 200, $headers);
    }
}
