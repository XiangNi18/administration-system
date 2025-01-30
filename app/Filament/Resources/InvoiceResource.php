<?php

namespace App\Filament\Resources;

use App\Filament\Resources\InvoiceResource\Pages;
use App\Models\Invoice;
use App\Models\Slip;
use Filament\Forms;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Resources\Resource;
use Filament\Tables;

class InvoiceResource extends Resource
{
    protected static ?string $model = Invoice::class;

    protected static ?string $navigationIcon = 'heroicon-o-document';
    protected static ?string $navigationGroup = 'Master Data';
    protected static ?string $navigationLabel = 'Invoice';
    protected static ?int $navigationSort = 5;

    public static function form(Forms\Form $form): Forms\Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('invoice_number')
                    ->label('Invoice Code')
                    ->disabled(),
                    Forms\Components\Select::make('customer_id')
                    ->label('Customer')
                    ->relationship('customer', 'name')
                    ->required()
                    ->reactive()
                    ->afterStateUpdated(function (callable $set) {
                        // Reset slip selection ketika customer berubah
                        $set('selected_slips', []);
                    }),
                Forms\Components\DatePicker::make('invoice_date')
                    ->required()
                    ->default(now())
                    ->label('Tanggal Invoice'),
                    Forms\Components\CheckboxList::make('selected_slips')
                    ->label('Pilih Slip')
                    ->options(function ($get) {
                        $customerId = $get('customer_id');
                        if ($customerId) {
                            return Slip::where('customer_id', $customerId)
                                ->whereNull('invoice_id')
                                ->get()
                                ->mapWithKeys(function ($slip) {
                                    return [
                                        $slip->id => "{$slip->slip_code} - Netto: {$slip->bruto_bongkar} - {$slip->tara_bongkar}",
                                    ];
                                });
                        }
                        return [];
                    })
                    ->columns(1)
                    ->reactive()
                    ->afterStateUpdated(function ($state, callable $set, callable $get) {
                        $freightCost = $get('freight_cost') ?? 0;
                        $totalDpp = Slip::whereIn('id', $state)->get()->sum(function ($slip) use ($freightCost) {
                            return ($slip->bruto_bongkar - $slip->tara_bongkar) * $freightCost;
                        });
                        $set('total_dpp', $totalDpp);
                        $set('ppn', $totalDpp * 0.11);
                        $set('pph23', floor($totalDpp * 0.02));
                        $set('total_invoice', $totalDpp + ($totalDpp * 0.11) - floor($totalDpp * 0.02));
                    }),

Forms\Components\TextInput::make('freight_cost')
    ->label('Ongkos Angkut')
    ->numeric()
    ->required()
    ->reactive()
    ->afterStateUpdated(function ($state, callable $set, callable $get) {
        $selectedSlips = $get('selected_slips') ?? [];
        $totalDpp = Slip::whereIn('id', $selectedSlips)->get()->sum(function ($slip) use ($state) {
            return ($slip->bruto_bongkar - $slip->tara_bongkar) * $state;
        });
        $set('total_dpp', $totalDpp);
        $set('ppn', $totalDpp * 0.11);
        $set('pph23', floor($totalDpp * 0.02));
        $set('total_invoice', $totalDpp + ($totalDpp * 0.11) - floor($totalDpp * 0.02));
    }),
    Forms\Components\TextInput::make('total_dpp')
    ->label('Total DPP')
    ->numeric()
    ->required()
    ->reactive()
    ->afterStateUpdated(function ($state, callable $set, callable $get) {
        $selectedSlips = $get('selected_slips') ?? [];
        $freightCost = $get('freight_cost') ?? 0;
        $totalDpp = Slip::whereIn('id', $selectedSlips)->get()->sum(function ($slip) use ($freightCost) {
            return ($slip->bruto_bongkar - $slip->tara_bongkar) * $freightCost;
        });
        $set('total_dpp', $totalDpp);
    }),
    Forms\Components\TextInput::make('ppn')
    ->label('PPN (11%)')
    ->numeric()
    ->required()
    ->reactive()
    ->afterStateUpdated(function (callable $set, callable $get) {
        $totalDpp = $get('total_dpp') ?? 0;
        $set('ppn', $totalDpp * 0.11); // PPN dihitung 11% dari DPP
    }),

Forms\Components\TextInput::make('pph23')
    ->label('PPh 23 (2%)')
    ->numeric()
    ->required()
    ->reactive()
    ->afterStateUpdated(function (callable $set, callable $get) {
        $totalDpp = $get('total_dpp') ?? 0;
        $set('pph23', floor($totalDpp * 0.02)); // PPh 23 dihitung 2% dari DPP
    }),
    Forms\Components\TextInput::make('total_invoice')
    ->label('Total Invoice')
    ->numeric()
    ->required()
    ->reactive()
    ->afterStateUpdated(function ($state, callable $set, callable $get) {
        $totalDpp = $get('total_dpp') ?? 0;
        $ppn = $totalDpp * 0.11;
        $pph23 = floor($totalDpp * 0.02);
        $totalInvoice = $totalDpp + $ppn - $pph23;

        $set('total_invoice', $totalInvoice);
    }),
            ]);
    }

    public static function table(Tables\Table $table): Tables\Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('invoice_number')->label('Invoice Code')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('invoice_date')->label('Invoice Date')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('customer.name')->label('Customer')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('total_invoice')->label('Total Invoice')->money('idr')->sortable(),
                Tables\Columns\TextColumn::make('created_at')->label('Created At')->date()->sortable(),
            ])
            ->filters([])
            ->actions([Tables\Actions\EditAction::make(),Tables\Actions\Action::make('print')
            ->label('Print')
            ->icon('heroicon-o-printer')
            ->action(function ($record) {
                // Logika cetak, seperti mengarahkan ke rute cetak
                return redirect()->route('invoices.print', ['invoice' => $record->id]);
            }),])

            ->bulkActions([Tables\Actions\DeleteBulkAction::make()]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListInvoices::route('/'),
            'create' => Pages\CreateInvoice::route('/create'),
            'edit' => Pages\EditInvoice::route('/{record}/edit'),
        ];
    }
}
