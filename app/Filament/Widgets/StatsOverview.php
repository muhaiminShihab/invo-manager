<?php

namespace App\Filament\Widgets;

use App\Models\Customer;
use App\Models\Invoice;
use App\Models\User;
use Filament\Widgets\Concerns\InteractsWithPageFilters;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Carbon;

class StatsOverview extends BaseWidget
{
    use InteractsWithPageFilters;

    protected function getStats(): array
    {
        // Ensure filters exist before accessing them
        $startDate = isset($this->filters['startDate']) && !empty($this->filters['startDate'])
            ? Carbon::parse($this->filters['startDate'] . ' 00:00:00')
            : now()->startOfYear();

        $endDate = isset($this->filters['endDate']) && !empty($this->filters['endDate'])
            ? Carbon::parse($this->filters['endDate'] . ' 23:59:59')
            : now();

        // Query user count
        $totalUsers = User::whereBetween('created_at', [$startDate, $endDate])->count();

        // Query customer count
        $totalCustomers = Customer::whereBetween('created_at', [$startDate, $endDate])->count();

        // Query invoice count
        $totalInvoices = Invoice::whereBetween('date', [$startDate, $endDate])->count();

        // Query unpaid invoices
        $totalUnpaidInvoices = Invoice::where('status', 'unpaid')
            ->whereBetween('date', [$startDate, $endDate])
            ->count();

        // Query paid invoices
        $totalPaidInvoices = Invoice::where('status', 'paid')
            ->whereBetween('date', [$startDate, $endDate])
            ->count();

        // Calculate total amount
        $totalAmount = Invoice::whereBetween('date', [$startDate, $endDate])
            ->with('items')
            ->get()
            ->sum(fn($invoice) => $invoice->items->sum('final_price'));

        // Calculate total unpaid amount
        $totalUnpaidAmount = Invoice::whereBetween('date', [$startDate, $endDate])
            ->where('status', 'unpaid')
            ->with('items')
            ->get()
            ->sum(fn($invoice) => $invoice->items->sum('final_price'));

        // Calculate total paid amount
        $totalPaidAmount = Invoice::whereBetween('date', [$startDate, $endDate])
            ->where('status', 'paid')
            ->with('items')
            ->get()
            ->sum(fn($invoice) => $invoice->items->sum('final_price'));

        return [
            Stat::make('মোট ইউজার', $this->convertToBanglaNumber($totalUsers))
                ->color('success')
                ->icon('heroicon-o-users'),

            Stat::make('মোট গ্রাহক', $this->convertToBanglaNumber($totalCustomers))
                ->color('success')
                ->icon('heroicon-o-user-group'),

            Stat::make('মোট চালান', $this->convertToBanglaNumber($totalInvoices))
                ->color('info')
                ->icon('heroicon-o-document-text'),

            Stat::make('মোট পরিশোধিত চালান', $this->convertToBanglaNumber($totalPaidInvoices))
                ->color('danger')
                ->icon('heroicon-o-document-text'),

            Stat::make('মোট বাকি চালান', $this->convertToBanglaNumber($totalUnpaidInvoices))
                ->color('danger')
                ->icon('heroicon-o-document-text'),

            Stat::make('মোট টাকা', $this->convertToBanglaNumber($totalAmount) . ' ৳')
                ->color('success')
                ->icon('heroicon-o-banknotes'),

            Stat::make('মোট পরিশোধিত টাকা', $this->convertToBanglaNumber($totalPaidAmount) . ' ৳')
                ->color('success')
                ->icon('heroicon-o-banknotes'),

            Stat::make('মোট বাকি টাকা', $this->convertToBanglaNumber($totalUnpaidAmount) . ' ৳')
                ->color('success')
                ->icon('heroicon-o-banknotes'),
        ];
    }

    /**
     * Convert English numbers to Bangla numbers.
     */
    function convertToBanglaNumber($number, $decimalPlaces = 0)
    {
        $number = number_format($number, $decimalPlaces);
        $englishDigits = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9'];
        $banglaDigits  = ['০', '১', '২', '৩', '৪', '৫', '৬', '৭', '৮', '৯'];

        return str_replace($englishDigits, $banglaDigits, $number);
    }
}
