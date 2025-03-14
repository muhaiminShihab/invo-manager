<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class InvoiceController extends Controller
{
    public function print(Invoice $invoice)
    {
        return view('invoices.print', [
            'invoice' => $invoice->load(['customer', 'items']),
        ]);
    }

    public function download(Invoice $invoice)
    {
        $pdf = Pdf::loadView('invoices.print', [
            'invoice' => $invoice->load(['customer', 'items']),
        ]);

        return $pdf->download("Invoice-{$invoice->invoice_number}.pdf");
    }
}