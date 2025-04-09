<?php

namespace App\Filament\Pages\Settings;

use App\Services\ManageEnvService;
use App\Settings\MailSettings;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Pages\SettingsPage;
use Illuminate\Support\Facades\Artisan;

class Mail extends SettingsPage
{
    protected ?string $heading = 'মেইল সেটিংস';

    protected static ?string $title = 'মেইল সেটিংস';

    protected static ?string $slug = 'settings/mail';

    protected static string $settings = MailSettings::class;

    protected ManageEnvService $manageEnv;

    public function __construct()
    {
        $this->manageEnv = app(ManageEnvService::class);
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make()
                    ->schema([
                        Forms\Components\Select::make('mail_driver')
                            ->label('মেইল ড্রাইভার')
                            ->required()
                            ->options([
                                'smtp' => 'SMTP'
                            ])
                            ->default('smtp'),
                        Forms\Components\TextInput::make('mail_from_name')
                            ->label('প্রেরকের নাম')
                            ->placeholder('ইমেইল প্রেরকের নাম লিখুন')
                            ->required(),
                        Forms\Components\TextInput::make('mail_from_address')
                            ->label('প্রেরকের মেইল')
                            ->placeholder('যে ইমেইল ঠিকানা থেকে ইমেইল পাঠানো হবে')
                            ->required(),
                        Forms\Components\TextInput::make('mail_host')
                            ->label('মেইল হোস্ট')
                            ->default(fn() => config('mail.mailers.smtp.host'))
                            ->required(),
                        Forms\Components\TextInput::make('mail_port')
                            ->label('মেইল পোর্ট')
                            ->default(fn() => config('mail.mailers.smtp.port'))
                            ->required(),
                        Forms\Components\Select::make('mail_encryption')
                            ->label('মেইল এনক্রিপশন')
                            ->options([
                                'tls' => 'TLS',
                                'ssl' => 'SSL'
                            ]),
                        Forms\Components\TextInput::make('mail_username')
                            ->label('ইউজারনেম'),
                        Forms\Components\TextInput::make('mail_password')
                            ->label('পাসওয়ার্ড'),
                    ])->columns(2)
            ]);
    }

    protected function afterSave(): void
    {
        $data = $this->form->getState();

        $this->manageEnv->update('MAIL_MAILER', $data['mail_driver']);
        $this->manageEnv->update('MAIL_HOST', $data['mail_host']);
        $this->manageEnv->update('MAIL_PORT', $data['mail_port']);
        $this->manageEnv->update('MAIL_USERNAME', $data['mail_username'] ?? null);
        $this->manageEnv->update('MAIL_PASSWORD', $data['mail_password'] ?? null);
        $this->manageEnv->update('MAIL_ENCRYPTION', $data['mail_encryption'] ?? null);
        $this->manageEnv->update('MAIL_FROM_ADDRESS', $data['mail_from_address']);
        $this->manageEnv->update('MAIL_FROM_NAME', $data['mail_from_name']);

        Artisan::call('config:clear');
    }
}
