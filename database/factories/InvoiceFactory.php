<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class InvoiceFactory extends Factory
{
    public function definition(): array
    {
        return [
            // 'date' => fake()->dateTimeBetween('-12 months', 'now'),
            'invoice_number' => 'INV-' . fake()->unique()->numberBetween(1000, 9999),
            'status' => fake()->randomElement(['paid', 'unpaid']),
            'note' => fake()->optional()->sentence(),
        ];
    }
}