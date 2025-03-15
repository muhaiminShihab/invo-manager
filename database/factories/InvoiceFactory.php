<?php

namespace Database\Factories;

use App\Models\Customer;
use Illuminate\Database\Eloquent\Factories\Factory;

class InvoiceFactory extends Factory
{
    public function definition(): array
    {
        return [
            'date' => fake()->dateTimeBetween('-6 months', 'now'),
            'invoice_number' => 'INV-' . fake()->unique()->numberBetween(1000, 9999),
            'status' => fake()->randomElement(['paid', 'unpaid']),
            'note' => fake()->optional()->sentence(),
        ];
    }
}