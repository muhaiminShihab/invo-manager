<?php

namespace App\Filament\Widgets;

use App\Models\Invoice;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Carbon;
use Filament\Widgets\Concerns\InteractsWithPageFilters;

class InvoiceChart extends ChartWidget
{
    use InteractsWithPageFilters;

    protected static ?string $heading = 'মাসিক ইনভয়েস পরিসংখ্যান';

    protected int | string | array $columnSpan = 'full';

    protected function getData(): array
    {
        // Ensure filters exist before accessing them
        $startDate = isset($this->filters['startDate']) && !empty($this->filters['startDate'])
            ? Carbon::parse($this->filters['startDate'])->startOfDay()
            : now()->startOfYear();

        $endDate = isset($this->filters['endDate']) && !empty($this->filters['endDate'])
            ? Carbon::parse($this->filters['endDate'])->endOfDay()
            : now();

        // Fetch invoices grouped by month, filtered by date range
        $invoices = Invoice::selectRaw("
                MONTH(date) as month,
                COUNT(*) as total,
                SUM(CASE WHEN status = 'paid' THEN 1 ELSE 0 END) as paid,
                SUM(CASE WHEN status = 'unpaid' THEN 1 ELSE 0 END) as unpaid
            ")
            ->whereBetween('date', [$startDate, $endDate])
            ->groupBy('month')
            ->orderByRaw("month ASC")
            ->get()
            ->keyBy('month');

        // Generate structured data for all 12 months
        $data = collect(range(1, 12))->map(function ($month) use ($invoices) {
            return [
                'month' => Carbon::createFromDate(now()->year, $month, 1)->translatedFormat('F'),
                'total' => $invoices[$month]->total ?? 0,
                'paid' => $invoices[$month]->paid ?? 0,
                'unpaid' => $invoices[$month]->unpaid ?? 0,
            ];
        });

        return [
            'datasets' => [
                [
                    'label' => 'মোট চালান',
                    'data' => $data->pluck('total')->toArray(),
                    'borderColor' => '#36A2EB',
                    'backgroundColor' => 'rgba(54, 162, 235, 0.2)',
                ],
                [
                    'label' => 'পরিশোধিত চালান',
                    'data' => $data->pluck('paid')->toArray(),
                    'borderColor' => '#4CAF50',
                    'backgroundColor' => 'rgba(76, 175, 80, 0.2)',
                ],
                [
                    'label' => 'অপরিশোধিত চালান',
                    'data' => $data->pluck('unpaid')->toArray(),
                    'borderColor' => '#FF6384',
                    'backgroundColor' => 'rgba(255, 99, 132, 0.2)',
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
