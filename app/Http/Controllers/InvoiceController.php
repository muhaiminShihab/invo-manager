<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Setting;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class InvoiceController extends Controller
{
    public function print(Invoice $invoice)
    {
        $settings = Setting::get();

        return view('invoices.print', [
            'invoice' => $invoice->load(['customer', 'items']),
            'settings' => $settings,
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