<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;

class OrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Order::factory()
            ->count(20)
            ->create()
            ->each(function ($order) {
                // For each order, create between 1 to 5 order items
                $itemsCount = rand(1, 5);
                for ($i = 0; $i < $itemsCount; $i++) {
                    $product = Product::inRandomOrder()->first();
                    OrderItem::factory()->create([
                        'order_id' => $order->id,
                        'product_id' => $product->id,
                    ]);
                }
            });
    }
}
