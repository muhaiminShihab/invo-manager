<?php

namespace App\Filament\Resources;

use App\Filament\Resources\InvoiceResource\Pages;
use App\Models\Invoice;
use App\Models\Customer;
use App\Services\ConvertToBanglaService;
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
        // Define the function as a Closure inside form()
        $updateFinalPrice = function (callable $set, callable $get) {
            $quantity = (float) ($get('quantity') ?? 1);
            $price = (float) ($get('price') ?? 0);
            $discountType = $get('discount_type') ?? 'fixed';
            $discountValue = (float) ($get('discount_value') ?? 0);

            $subtotal = $quantity * $price;
            $discount = $discountType === 'percentage'
                ? ($subtotal * $discountValue) / 100
                : $discountValue;

            $finalPrice = max(0, $subtotal - $discount);
            $set('final_price', $finalPrice);
        };

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
                            ->label('আইটেমস')
                            ->relationship()
                            ->schema([
                                Forms\Components\TextInput::make('name')
                                    ->label('নাম')
                                    ->required(),
                                Forms\Components\TextInput::make('quantity')
                                    ->label('পরিমাণ')
                                    ->numeric()
                                    ->default(1)
                                    ->minValue(1)
                                    ->required()
                                    // ->live(onBlur: true)
                                    ->afterStateUpdated(fn($state, callable $set, callable $get) => $updateFinalPrice($set, $get)),
                                Forms\Components\TextInput::make('price')
                                    ->label('মূল্য')
                                    ->numeric()
                                    ->minValue(0)
                                    ->required()
                                    // ->live(onBlur: true)
                                    ->afterStateUpdated(fn($state, callable $set, callable $get) => $updateFinalPrice($set, $get)),
                                Forms\Components\Select::make('discount_type')
                                    ->label('ডিসকাউন্ট টাইপ')
                                    ->options([
                                        'fixed' => 'টাকা',
                                        'percentage' => 'শতকরা',
                                    ])
                                    ->default('fixed')
                                    ->required()
                                    // ->live(onBlur: true)
                                    ->afterStateUpdated(fn($state, callable $set, callable $get) => $updateFinalPrice($set, $get)),
                                Forms\Components\TextInput::make('discount_value')
                                    ->label('ডিসকাউন্ট')
                                    ->numeric()
                                    ->default(0)
                                    ->minValue(0)
                                    // ->live(onBlur: true)
                                    ->afterStateUpdated(fn($state, callable $set, callable $get) => $updateFinalPrice($set, $get)),
                                Forms\Components\TextInput::make('final_price')
                                    ->label('সর্বমোট')
                                    ->numeric(),
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
                    ->getStateUsing(fn($record) => ConvertToBanglaService::number($record->items()->count())),
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
                        'paid' => 'primary',
                        'unpaid' => 'danger',
                    })
                    ->formatStateUsing(fn(string $state): string => match ($state) {
                        'paid' => 'পরিশোধিত',
                        'unpaid' => 'বাকি',
                    })
                    ->icon(fn(string $state): string => match ($state) {
                        'paid' => 'heroicon-s-check-badge',
                        'unpaid' => 'heroicon-s-x-circle',
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
                Tables\Filters\Filter::make('date')
                    ->form([
                        Forms\Components\DatePicker::make('created_from')
                            ->label('তারিখ থেকে'),
                        Forms\Components\DatePicker::make('created_until')
                            ->label('তারিখ পর্যন্ত')
                            ->default(now()),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['created_from'],
                                fn(Builder $query, $date): Builder => $query->whereDate('date', '>=', $date),
                            )
                            ->when(
                                $data['created_until'],
                                fn(Builder $query, $date): Builder => $query->whereDate('date', '<=', $date),
                            );
                    })
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
