<?php

use App\Models\Invoice;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('invoice_items', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Invoice::class)->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->integer('quantity')->default(1);
            $table->double('price')->default(0);
            $table->enum('discount_type', ['percentage', 'fixed'])->default('fixed');
            $table->double('discount_value')->default(0);
            $table->double('final_price')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoice_items');
    }
};
