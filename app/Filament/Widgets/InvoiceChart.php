<?php

namespace App\Filament\Widgets;

use App\Models\Invoice;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Carbon;

class InvoiceChart extends ChartWidget
{
    protected static ?string $heading = 'চালান পরিসংখ্যান';

    protected int | string | array $columnSpan = 'full';

    protected function getData(): array
    {
        $data = Invoice::query()
            ->selectRaw('DATE_FORMAT(date, "%M") as month')
            ->selectRaw('COUNT(*) as total')
            ->selectRaw('SUM(CASE WHEN status = "paid" THEN 1 ELSE 0 END) as paid')
            ->selectRaw('SUM(CASE WHEN status = "unpaid" THEN 1 ELSE 0 END) as unpaid')
            ->whereYear('date', Carbon::now()->year)
            ->groupBy('month', 'date')
            ->orderBy('date')
            ->get();

        return [
            'datasets' => [
                [
                    'label' => 'পরিশোধিত',
                    'data' => $data->pluck('paid'),
                    'borderColor' => '#10B981',
                    'tension' => 0.3,
                    'fill' => false,
                ],
                [
                    'label' => 'বাকি',
                    'data' => $data->pluck('unpaid'),
                    'borderColor' => '#EF4444',
                    'tension' => 0.3,
                    'fill' => false,
                ],
            ],
            'labels' => $data->pluck('month'),
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
