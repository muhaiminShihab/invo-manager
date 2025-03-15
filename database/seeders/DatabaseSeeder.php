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

        $customers->each(function ($customer) {
            // Create 1-5 invoices for each customer
            $invoices = Invoice::factory(rand(1, 5)) // Use rand() instead of fake()
                ->for($customer)
                ->create();

            $invoices->each(function ($invoice) {
                // Create 3-10 items for each invoice
                $invoice->items()->createMany(
                    InvoiceItem::factory(rand(3, 10))->make()->map(fn($item) => $item->toArray())->toArray()
                );
            });
        });

        $this->call([
            UserSeeder::class,
            SettingSeeder::class,
        ]);
    }
}
