<?php

namespace App\Filament\Resources;

use App\Filament\Resources\InvoiceResource\Pages;
use App\Models\Invoice;
use App\Models\Customer;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class InvoiceResource extends Resource
{
    protected static ?string $model = Invoice::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static ?string $navigationLabel = 'চালান সমূহ';

    protected static ?string $modelLabel = 'চালান';

    protected static ?string $pluralModelLabel = 'চালান সমূহ';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make()
                    ->schema([
                        Forms\Components\DatePicker::make('date')
                            ->label('তারিখ')
                            ->required()
                            ->default(now())
                            ->maxDate(now()),
                        Forms\Components\TextInput::make('invoice_number')
                            ->label('চালান নম্বর')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->default(fn() => 'INV-' . now()->format('Ymd-His'))
                            // ->disabled()
                            ->dehydrated(),
                        Forms\Components\Select::make('customer_id')
                            ->label('গ্রাহক')
                            ->relationship('customer', 'name')
                            ->searchable()
                            ->preload()
                            ->required(),
                        Forms\Components\Select::make('status')
                            ->label('স্ট্যাটাস')
                            ->options([
                                'unpaid' => 'বাকি',
                                'paid' => 'পরিশোধিত',
                            ])
                            ->default('unpaid')
                            ->required(),
                        Forms\Components\Textarea::make('note')
                            ->label('নোট')
                            ->columnSpanFull(),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('আইটেম সমূহ')
                    ->schema([
                        Forms\Components\Repeater::make('items')
                            ->relationship()
                            ->schema([
                                Forms\Components\TextInput::make('name')
                                    ->label('নাম')
                                    ->required(),
                                Forms\Components\TextInput::make('quantity')
                                    ->label('পরিমাণ')
                                    ->numeric()
                                    ->default(1)
                                    ->required()
                                    ->live(),
                                Forms\Components\TextInput::make('price')
                                    ->label('মূল্য')
                                    ->numeric()
                                    ->required()
                                    ->live(),
                                Forms\Components\Select::make('discount_type')
                                    ->label('ডিসকাউন্ট টাইপ')
                                    ->options([
                                        'fixed' => 'টাকা',
                                        'percentage' => 'শতকরা',
                                    ])
                                    ->default('fixed')
                                    ->required()
                                    ->live(),
                                Forms\Components\TextInput::make('discount_value')
                                    ->label('ডিসকাউন্ট')
                                    ->numeric()
                                    ->default(0)
                                    ->live(),
                                Forms\Components\TextInput::make('final_price')
                                    ->label('সর্বমোট')
                                    ->numeric()
                                    ->live()
                                    ->afterStateUpdated(function ($state, $component) {
                                        $container = $component->getContainer();
                                        if (!$container) return;

                                        $state = $container->getState();

                                        $quantity = (float) ($state['quantity'] ?? 1);
                                        $price = (float) ($state['price'] ?? 0);
                                        $discountType = $state['discount_type'] ?? 'fixed';
                                        $discountValue = (float) ($state['discount_value'] ?? 0);

                                        $subtotal = $quantity * $price;
                                        $discount = $discountType === 'percentage'
                                            ? ($subtotal * $discountValue) / 100
                                            : $discountValue;

                                        $component->state(max(0, $subtotal - $discount));
                                    }),
                            ])
                            ->columns(6)
                            ->defaultItems(1)
                            ->minItems(1)
                            ->live(true)
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('date')
                    ->label('তারিখ')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('invoice_number')
                    ->label('চালান নম্বর')
                    ->searchable(),
                Tables\Columns\TextColumn::make('customer.name')
                    ->label('গ্রাহক')
                    ->searchable(),
                Tables\Columns\TextColumn::make('items_count')
                    ->label('আইটেম')
                    ->counts('items'),
                Tables\Columns\TextColumn::make('items_amount')
                    ->label('সর্বমোট')
                    ->money('BDT')
                    ->getStateUsing(function ($record) {
                        return $record->items->sum('final_price');
                    }),
                Tables\Columns\TextColumn::make('status')
                    ->label('স্ট্যাটাস')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'paid' => 'success',
                        'unpaid' => 'danger',
                    })
                    ->formatStateUsing(fn(string $state): string => match ($state) {
                        'paid' => 'পরিশোধিত',
                        'unpaid' => 'বাকি',
                    }),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->label('স্ট্যাটাস')
                    ->options([
                        'unpaid' => 'বাকি',
                        'paid' => 'পরিশোধিত',
                    ]),
                Tables\Filters\SelectFilter::make('customer_id')
                    ->label('গ্রাহক')
                    ->relationship('customer', 'name')
                    ->searchable()
                    ->preload(),
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\ViewAction::make(),
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

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListInvoices::route('/'),
            'create' => Pages\CreateInvoice::route('/create'),
            'edit' => Pages\EditInvoice::route('/{record}/edit'),
            'view' => Pages\ViewInvoice::route('/{record}'),
        ];
    }
}
