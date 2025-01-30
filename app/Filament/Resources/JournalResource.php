<?php

namespace App\Filament\Resources;

use App\Filament\Resources\JournalResource\Pages;
use App\Models\Journal;
use App\Models\Account;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Tables;

class JournalResource extends Resource
{
    protected static ?string $model = Journal::class;

    protected static ?string $navigationGroup = 'Master Data';
    protected static ?string $navigationLabel = 'Jurnal Umum';
    protected static ?int $navigationSort = 6;

    public static function form(Forms\Form $form): Forms\Form
    {
        return $form
            ->schema([
                // Header form untuk jurnal umum
                Forms\Components\TextInput::make('description')
                    ->label('Deskripsi Jurnal')
                    ->required(),

                Forms\Components\DatePicker::make('date')
                    ->label('Tanggal Jurnal')
                    ->required(),

                // Detail entri jurnal (menggunakan Repeater)
                Forms\Components\Repeater::make('journal_entries')
                    ->label('Detail Jurnal')
                    ->relationship('journalEntries') // Menghubungkan ke relasi yang benar
                    ->schema([
                        Forms\Components\Select::make('account_id')
                            ->label('Akun')
                            ->relationship('account', 'name') // Menggunakan relasi langsung
                            ->required(),

                        Forms\Components\TextInput::make('debit')
                            ->label('Debit')
                            ->numeric()
                            ->default(0)
                            ->required(),

                        Forms\Components\TextInput::make('credit')
                            ->label('Kredit')
                            ->numeric()
                            ->default(0)
                            ->required(),
                    ])
                    ->createItemButtonLabel('Tambah Entri Jurnal')
                    ->columns(3)
                    ->required(),
            ]);
    }

    public static function table(Tables\Table $table): Tables\Table
{
    return $table
        ->columns([
            Tables\Columns\TextColumn::make('id')
                ->label('ID')
                ->sortable(),

            Tables\Columns\TextColumn::make('date')
                ->label('Tanggal Jurnal')
                ->sortable(),

            Tables\Columns\TextColumn::make('description')
                ->label('Deskripsi'),

            Tables\Columns\TextColumn::make('sum_debit') // Menggunakan accessor
                ->label('Total Debit')
                ->sortable(),

            Tables\Columns\TextColumn::make('sum_credit') // Menggunakan accessor
                ->label('Total Kredit')
                ->sortable(),
        ]);
}

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListJournals::route('/'),
            'create' => Pages\CreateJournal::route('/create'),
            'edit' => Pages\EditJournal::route('/{record}/edit'),
        ];
    }
}
