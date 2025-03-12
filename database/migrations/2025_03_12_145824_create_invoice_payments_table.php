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
        Schema::create('invoice_payments', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Invoice::class)->constrained()->cascadeOnDelete();
            $table->date('date');
            $table->double('amount')->default(0);
            $table->text('note')->nullable();
            $table->string('attachment')->nullable();
            $table->string('payment_method')->default('cash');
            $table->string('payment_reference')->nullable();
            $table->enum('status', ['paid', 'unpaid'])->default('unpaid');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoice_payments');
    }
};
