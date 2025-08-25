<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class OrderFactory extends Factory
{
    public function definition(): array
    {
        return [
            'transaction_time' => $this->faker->dateTimeBetween('-3 months', 'now'),
            'total_price'      => 0, // akan di-update setelah insert OrderItems
            'total_item'       => 0, // akan di-update setelah insert OrderItems
            'kasir_id'         => 1, // nanti bisa di-random di Seeder
            'payment_method'   => $this->faker->randomElement(['Tunai', 'QRIS']),
        ];
    }
}
