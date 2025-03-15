<?php

namespace App\Filament\Widgets;

use App\Models\Customer;
use App\Models\Invoice;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Carbon;

class StatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        $currentYear = Carbon::now()->year;

        return [
            Stat::make('মোট গ্রাহক', Customer::whereYear('created_at', $currentYear)->count())
                ->description($currentYear . ' সালের মোট গ্রাহক')
                ->descriptionIcon('heroicon-m-users')
                ->color('success'),

            Stat::make('মোট চালান', Invoice::whereYear('date', $currentYear)->count())
                ->description($currentYear . ' সালের মোট চালান')
                ->descriptionIcon('heroicon-m-document-text')
                ->color('info'),

            Stat::make('মোট বাকি', Invoice::where('status', 'unpaid')
                ->whereYear('date', $currentYear)
                ->count())
                ->description($currentYear . ' সালের বাকি চালান')
                ->descriptionIcon('heroicon-m-exclamation-circle')
                ->color('danger'),

            Stat::make('মোট টাকা', number_format(Invoice::with('items')
                ->whereYear('date', $currentYear)
                ->get()
                ->sum(function ($invoice) {
                    return $invoice->items->sum('final_price');
                }), 2) . ' ৳')
                ->description($currentYear . ' সালের মোট টাকা')
                ->descriptionIcon('heroicon-m-banknotes')
                ->color('success'),
        ];
    }
}
