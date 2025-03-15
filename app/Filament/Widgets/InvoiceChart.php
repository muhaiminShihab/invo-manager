<?php

namespace App\Filament\Widgets;

use App\Models\Invoice;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Carbon;

class InvoiceChart extends ChartWidget
{
    protected static ?string $heading = 'মাসিক ইনভয়েস পরিসংখ্যান';

    protected int | string | array $columnSpan = 'full';

    protected function getData(): array
    {
        // Fetch invoices grouped by month based on the `date` column
        $invoices = Invoice::selectRaw("
                MONTH(date) as month,
                COUNT(*) as total,
                SUM(CASE WHEN status = 'paid' THEN 1 ELSE 0 END) as paid,
                SUM(CASE WHEN status = 'unpaid' THEN 1 ELSE 0 END) as unpaid
            ")
            ->whereYear('date', now()->year) // Use `date` instead of `created_at`
            ->groupBy('month')
            ->orderByRaw("month ASC")
            ->get()
            ->keyBy('month'); // Index data by month for easy lookup

        // Generate structured data for all 12 months
        $data = collect(range(1, 12))->map(function ($month) use ($invoices) {
            return [
                'month' => Carbon::createFromDate(now()->year, $month, 1)->format('F'),
                'total' => $invoices[$month]->total ?? 0,
                'paid' => $invoices[$month]->paid ?? 0,
                'unpaid' => $invoices[$month]->unpaid ?? 0,
            ];
        });

        return [
            'datasets' => [
                [
                    'label' => 'Total Invoices',
                    'data' => $data->pluck('total')->toArray(),
                    'borderColor' => '#36A2EB',
                ],
                [
                    'label' => 'Paid Invoices',
                    'data' => $data->pluck('paid')->toArray(),
                    'borderColor' => '#4CAF50',
                ],
                [
                    'label' => 'Unpaid Invoices',
                    'data' => $data->pluck('unpaid')->toArray(),
                    'borderColor' => '#FF6384',
                ],
            ],
            'labels' => $data->pluck('month')->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
