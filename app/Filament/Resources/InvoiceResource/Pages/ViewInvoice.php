<?php

namespace App\Filament\Resources\InvoiceResource\Pages;

use App\Filament\Resources\InvoiceResource;
use Filament\Resources\Pages\ViewRecord;
use Filament\Actions\Action;

class ViewInvoice extends ViewRecord
{
    protected static string $resource = InvoiceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('print')
                ->label('প্রিন্ট')
                ->icon('heroicon-o-printer')
                ->url(fn () => route('invoice.print', $this->record))
                ->openUrlInNewTab(),
            // Action::make('download')
            //     ->label('ডাউনলোড')
            //     ->icon('heroicon-o-arrow-down-tray')
            //     ->url(fn () => route('invoice.download', $this->record))
            //     ->openUrlInNewTab(),
        ];
    }
}