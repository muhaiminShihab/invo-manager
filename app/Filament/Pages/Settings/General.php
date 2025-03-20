<?php

namespace App\Filament\Pages\Settings;

use App\Services\ManageEnvService;
use App\Settings\GeneralSettings;
use Filament\Forms;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Split;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Pages\SettingsPage;
use Illuminate\Support\Facades\Artisan;

class General extends SettingsPage
{
    protected ?string $heading = 'জেনারেল সেটিংস';

    protected static ?string $title = 'জেনারেল সেটিংস';

    protected static ?string $slug = 'settings/general';

    protected int | string | array $columnSpan = 'full';

    protected static string $settings = GeneralSettings::class;

    protected ManageEnvService $manageEnv;

    public function __construct()
    {
        $this->manageEnv = app(ManageEnvService::class);
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Split::make([
                    Section::make([
                        TextInput::make('site_name')
                            ->required()
                            ->label('সাইটের নাম')
                            ->default(fn() => config('app.name')),
                        TextInput::make('phone')
                            ->required()
                            ->label('ফোন নাম্বার'),
                        Textarea::make('address')
                            ->required()
                            ->label('ঠিকানা'),
                    ])->columnSpan(2),
                    Section::make([
                        FileUpload::make('site_logo')
                            ->image()
                            ->label('লোগো'),
                    ])->columnSpan(1),
                ])->from('md')->columnSpanFull()
            ]);
    }

    protected function afterSave(): void
    {
        $data = $this->form->getState();

        $this->manageEnv->update('APP_NAME', $data['site_name']);

        Artisan::call('config:clear');
    }
}
