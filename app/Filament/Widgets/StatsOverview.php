<?php

namespace App\Filament\Widgets;

use App\Models\Customer;
use App\Models\Invoice;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('মোট গ্রাহক', Customer::count())
                ->description('সর্বমোট গ্রাহক সংখ্যা')
                ->descriptionIcon('heroicon-m-users')
                ->color('success'),

            Stat::make('মোট চালান', Invoice::count())
                ->description('সর্বমোট চালান সংখ্যা')
                ->descriptionIcon('heroicon-m-document-text')
                ->color('info'),

            Stat::make('মোট বাকি', Invoice::where('status', 'unpaid')->count())
                ->description('বাকি চালান সংখ্যা')
                ->descriptionIcon('heroicon-m-exclamation-circle')
                ->color('danger'),

            Stat::make('মোট টাকা', number_format(Invoice::with('items')->get()->sum(function ($invoice) {
                return $invoice->items->sum('final_price');
            }), 2) . ' ৳')
                ->description('সর্বমোট চালানের পরিমাণ')
                ->descriptionIcon('heroicon-m-banknotes')
                ->color('success'),
        ];
    }
}
