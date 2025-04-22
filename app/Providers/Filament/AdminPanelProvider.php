<?php

namespace App\Providers\Filament;

use App\Filament\Resources\CustomerResource;
use App\Filament\Resources\InvoiceResource;
use App\Filament\Resources\SettingResource;
use App\Filament\Resources\UserResource;
use App\Models\Setting;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Navigation\NavigationBuilder;
use Filament\Navigation\NavigationGroup;
use Filament\Navigation\NavigationItem;
use Filament\Pages;
use Filament\Pages\Dashboard;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Widgets;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use App\Services\Navigation\AdminNavigationService;
use Joaopaulolndev\FilamentEditProfile\FilamentEditProfilePlugin;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        $logo = Setting::where('name', 'site_logo')->first();
        if ($logo && !empty($logo->payload) && $logo->payload !== "null") {
            $panel->brandLogo(asset('storage/' . trim($logo->payload, '"')));
        } else {
            $panel->brandLogo(asset('media/logo.png'));
        }

        return $panel
            ->default()
            ->id('admin')
            ->path('app')
            ->login()
            ->darkMode(false)
            ->spa()
            ->colors([
                'primary' => Color::Indigo,
            ])
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([
                // Pages\Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->widgets([
                // Widgets\AccountWidget::class,
                // Widgets\FilamentInfoWidget::class,
            ])
            ->brandLogoHeight('3rem')
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ])
            ->sidebarWidth('250px')
            ->navigation(function (NavigationBuilder $builder): NavigationBuilder {
                return app(AdminNavigationService::class)->build($builder);
            })
            ->plugins([
                FilamentEditProfilePlugin::make()
                    ->slug('my-profile')
            ]);
    }
}
