<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;

class OrderSeeder extends Seeder
{
    public function run(): void
    {
        // ambil semua produk & user (kasir)
        $products = Product::all();
        $kasirs = User::all();

        // bikin 50 order dummy
        Order::factory(50)->create()->each(function ($order) use ($products, $kasirs) {
            // assign kasir random
            $order->kasir_id = $kasirs->random()->id;
            $order->save();

            $totalPrice = 0;
            $totalItem = 0;

            // random berapa item dalam order (1-5)
            $items = $products->random(rand(1, 5));

            foreach ($items as $product) {
                $qty = rand(1, 5);
                $itemTotal = $product->price * $qty;

                OrderItem::create([
                    'order_id'   => $order->id,
                    'product_id' => $product->id,
                    'quantity'   => $qty,
                    'total_price'=> $itemTotal,
                ]);

                $totalPrice += $itemTotal;
                $totalItem  += $qty;
            }

            // update total order
            $order->update([
                'total_price' => $totalPrice,
                'total_item'  => $totalItem,
            ]);
        });
    }
}
