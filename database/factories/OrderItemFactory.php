<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class OrderItemFactory extends Factory
{
    public function definition(): array
    {
        return [
            'order_id'    => null, // diisi di Seeder
            'product_id'  => null, // diisi di Seeder
            'quantity'    => $this->faker->numberBetween(1, 5),
            'total_price' => 0, // diisi sesuai harga * qty di Seeder
        ];
    }
}
