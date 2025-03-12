<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\RelationManagers;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Hash;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';

    protected static ?string $navigationLabel = 'ইউজার সমূহ';

    protected static ?string $modelLabel = 'ইউজার';

    protected static ?string $pluralModelLabel = 'ইউজার সমূহ';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label('নাম')
                    ->required()
                    ->maxLength(50),
                Forms\Components\TextInput::make('email')
                    ->label('ইমেইল')
                    ->email()
                    ->required()
                    ->maxLength(50),
                Forms\Components\TextInput::make('phone')
                    ->label('ফোন')
                    ->tel()
                    ->required()
                    ->maxLength(15),
                Forms\Components\TextInput::make('password')
                    ->label('পাসওয়ার্ড')
                    ->password()
                    ->afterStateHydrated(function (Forms\Components\TextInput $component, $state) {
                        $component->state('');
                    })
                    ->dehydrateStateUsing(fn (?string $state): string =>
                        filled($state) ? Hash::make($state) : null
                    )
                    ->dehydrated(fn (?string $state): bool => filled($state))
                    ->required(fn (string $operation): bool => $operation === 'create')
                    ->revealable()
                    ->maxLength(255),
                Forms\Components\Select::make('status')
                    ->label('স্ট্যাটাস')
                    ->options([
                        'active' => 'সচল',
                        'inactive' => 'অচল',
                    ])
                    ->default('active')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('নাম')
                    ->searchable(),
                Tables\Columns\TextColumn::make('email')
                    ->label('ইমেইল')
                    ->searchable(),
                Tables\Columns\TextColumn::make('phone')
                    ->label('ফোন')
                    ->searchable(),
                Tables\Columns\TextColumn::make('status')
                    ->label('স্ট্যাটাস')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'active' => 'primary',
                        'inactive' => 'danger',
                    })
                    ->formatStateUsing(fn(string $state): string => $state == 'active' ? 'সচল' : 'অচল'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make()
                    ->hidden(fn (User $record): bool => $record->id === 1),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->hidden(fn (?Collection $records): bool =>
                            $records?->contains('id', 1) ?? false
                        ),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageUsers::route('/'),
        ];
    }
}
