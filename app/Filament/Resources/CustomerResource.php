<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CustomerResource\Pages;
use App\Filament\Resources\CustomerResource\RelationManagers;
use App\Models\Customer;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CustomerResource extends Resource
{
    protected static ?string $model = Customer::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';

    protected static ?string $navigationLabel = 'গ্রাহক সমূহ';

    protected static ?string $modelLabel = 'গ্রাহক';

    protected static ?string $pluralModelLabel = 'গ্রাহক সমূহ';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label('নাম')
                    ->required()
                    ->maxLength(50),
                Forms\Components\TextInput::make('phone')
                    ->label('ফোন')
                    ->tel()
                    ->required()
                    ->maxLength(15),
                Forms\Components\TextInput::make('email')
                    ->label('ইমেইল')
                    ->email()
                    ->maxLength(50),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('নাম')
                    ->searchable(),
                Tables\Columns\TextColumn::make('phone')
                    ->label('ফোন')
                    ->searchable(),
                Tables\Columns\TextColumn::make('email')
                    ->label('ইমেইল')
                    ->searchable()
                    ->placeholder('N/A'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\DeleteAction::make(),
                ]),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageCustomers::route('/'),
        ];
    }
}
