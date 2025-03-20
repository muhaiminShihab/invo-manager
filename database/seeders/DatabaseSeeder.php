<?php

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Create 50 customers
        $customers = Customer::factory(50)->create();

        $customers->each(
            fn($customer) => Invoice::factory(rand(1, 5))
                ->for($customer)
                ->create(['date' => now()->subDays(rand(0, 365))]) // Generates a date within the past year
                ->each(fn($invoice) => $invoice->items()->createMany(
                    InvoiceItem::factory(rand(3, 10))->make()->toArray()
                ))
        );

        $this->call([
            UserSeeder::class,
        ]);
    }
}
