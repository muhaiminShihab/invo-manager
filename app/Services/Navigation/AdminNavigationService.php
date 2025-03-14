<?php

namespace App\Services\Navigation;

use App\Filament\Resources\CustomerResource;
use App\Filament\Resources\InvoiceResource;
use App\Filament\Resources\SettingResource;
use App\Filament\Resources\UserResource;
use Filament\Navigation\NavigationBuilder;
use Filament\Navigation\NavigationGroup;
use Filament\Navigation\NavigationItem;
use Filament\Pages\Dashboard;

class AdminNavigationService
{
    public function build(NavigationBuilder $builder): NavigationBuilder
    {
        return $builder
            ->items([
                $this->getDashboardItem(),
            ])
            ->groups([
                $this->getUserManagementGroup(),
                $this->getInvoiceManagementGroup(),
                $this->getSettingsGroup(),
            ]);
    }

    private function getDashboardItem(): NavigationItem
    {
        return NavigationItem::make('ড্যাশবোর্ড')
            ->icon('heroicon-o-home')
            ->isActiveWhen(fn(): bool => request()->routeIs('filament.admin.pages.dashboard'))
            ->url(fn(): string => Dashboard::getUrl());
    }

    private function getUserManagementGroup(): NavigationGroup
    {
        return NavigationGroup::make('ইউজার ম্যানেজমেন্ট')
            ->items([
                ...UserResource::getNavigationItems(),
                ...CustomerResource::getNavigationItems(),
            ]);
    }

    private function getInvoiceManagementGroup(): NavigationGroup
    {
        return NavigationGroup::make('ইনভয়েজ ম্যানেজমেন্ট')
            ->items([
                NavigationItem::make('চালান সমূহ')
                    ->icon('heroicon-o-document-text')
                    ->url(fn(): string => InvoiceResource::getUrl('index'))
                    ->isActiveWhen(fn(): bool => request()->routeIs('filament.admin.resources.invoices.index'))
                    ->sort(1),
                NavigationItem::make('চালান তৈরী করুন')
                    ->icon('heroicon-o-plus-circle')
                    ->url(fn(): string => InvoiceResource::getUrl('create'))
                    ->isActiveWhen(fn(): bool => request()->routeIs('filament.admin.resources.invoices.create'))
                    ->sort(2)
            ]);
    }

    private function getSettingsGroup(): NavigationGroup
    {
        return NavigationGroup::make('সেটিং সমূহ')
            ->items([
                ...SettingResource::getNavigationItems(),
            ]);
    }
}