<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class InvoiceItemFactory extends Factory
{
    public function definition(): array
    {
        $quantity = fake()->numberBetween(1, 10);
        $price = fake()->numberBetween(100, 1000);
        $discountType = fake()->randomElement(['fixed', 'percentage']);
        $discountValue = $discountType === 'fixed'
            ? fake()->numberBetween(0, 100)
            : fake()->numberBetween(0, 20);

        $subtotal = $quantity * $price;
        $discount = $discountType === 'percentage'
            ? ($subtotal * $discountValue) / 100
            : $discountValue;

        return [
            'name' => fake()->word(),
            'quantity' => $quantity,
            'price' => $price,
            'discount_type' => $discountType,
            'discount_value' => $discountValue,
            'final_price' => max(0, $subtotal - $discount),
        ];
    }
}