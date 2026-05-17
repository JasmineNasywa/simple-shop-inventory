<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TransactionResource\Pages;
use App\Filament\Resources\TransactionResource\RelationManagers;
use App\Models\Transaction;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Section;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Forms\Components\DatePicker;


use App\Models\Product;

class TransactionResource extends Resource
{
    protected static ?string $model = Transaction::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
    return $form
        ->schema([
            // Bagian Atas
            Section::make('Informasi')
                ->schema([
                    TextInput::make('reference_number')->default('TR-' . date('YmdHis'))->readonly(),
                    TextInput::make('cashier_name')->default(fn () => auth()->user()?->name)->readOnly(),
                ])->columns(2),

            // Bagian Tengah (REPEATER)
            Section::make('Detail Barang')
                ->schema([
                    Repeater::make('details')
                        ->relationship()
                        ->live() // WAJIB: Agar setiap ada baris baru/hapus baris, total dihitung ulang
                        ->afterStateUpdated(function ($state, $set) {
                            // Ini u/ hitung total harga
                            $total = collect($state)->reduce(function ($total, $item) {
                                return $total + (($item['quantity'] ?? 0) * ($item['price'] ?? 0));
                            }, 0);
                            $set('total_price', $total);
                        })
                        ->schema([
                            Select::make('product_id')
                                ->label('Pilih Produk')
                                ->options(\App\Models\Product::query()
                                ->selectRaw("id, CONCAT(name, ' (Stok: ', stock, ')') as name_with_stock") //spy muncul stok saat mau pilih
                                ->pluck('name_with_stock', 'id'))
                                ->required()
                                ->reactive()
                                ->distinct()
                                ->searchable() 
                                ->preload() //biar langsung load sblm di klik
                                ->disableOptionsWhenSelectedInSiblingRepeaterItems()
                                ->afterStateUpdated(function ($state, $set) {
                                    $product = \App\Models\Product::find($state);
                                    $set('price', $product?->price ?? 0);
                                }),
                            TextInput::make('quantity')
                                ->numeric()
                                ->default(1)
                                ->live(onBlur: true) //ini lupa de keknya biar ga lemot 
                                ->required()
                                ->rules([
                                fn ($get) => function (string $attribute, $value, $fail) use ($get) {
                                    $productId = $get('product_id'); // Ambil ID produk di baris ini
                                    if ($productId) {
                                        $product = \App\Models\Product::find($productId);
                                        if ($product && $value > $product->stock) {
                                            $fail("Stok tidak cukup! Sisa stok: {$product->stock}");
                                        }
                                    }
                                },]),
                            TextInput::make('price')
                                ->numeric()
                                ->prefix('Rp')
                                ->readonly(),
                        ])->columns(3),
                ]),

            // Bagian Bawah (TOTAL)
            TextInput::make('total_price')
                ->label('Total yang Harus Dibayar')
                ->numeric()
                ->prefix('Rp')
                ->readonly()
                // ini biar kalau buka data lama, angkanya nggak nol
                ->afterStateHydrated(function (TextInput $component, $get, $state) { // Tambahkan $state di sini
                // $get('details') unmtuk mengambil data dr repeater
                $details = $get('details') ?? [];
                
                $total = collect($details)->reduce(function ($total, $item) {
                    $price = (float) ($item['price'] ?? 0);
                    $quantity = (int) ($item['quantity'] ?? 0);
                    return $total + ($price * $quantity);
                }, 0);

                $component->state($total);
                    }),
        ]);
}

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
            TextColumn::make('reference_number')->label('No. Transaksi'),
            TextColumn::make('cashier_name')->label('Kasir'), 
            TextColumn::make('total_price')->money('idr'),
            TextColumn::make('created_at')->label('Tanggal')->dateTime(),
            ])
            ->filters([
                Filter::make('created_at')
                ->form([
                    DatePicker::make('dari_tanggal'),
                    DatePicker::make('sampai_tanggal'),
                ])
                ->query(function (Builder $query, array $data): Builder {
                    return $query
                        ->when(
                            $data['dari_tanggal'],
                            fn (Builder $query, $date): Builder => $query->whereDate('created_at', '>=', $date),
                        )
                        ->when(
                            $data['sampai_tanggal'],
                            fn (Builder $query, $date): Builder => $query->whereDate('created_at', '<=', $date),
                        );
                }),

            SelectFilter::make('product')
                ->label('Produk Terbeli')
                ->relationship('details.product', 'name') // spy bs menembus relasi ke tabel detail
                ->searchable() 
                ->preload(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
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
            'index' => Pages\ListTransactions::route('/'),
            'create' => Pages\CreateTransaction::route('/create'),
            'edit' => Pages\EditTransaction::route('/{record}/edit'),
        ];
    }
}
