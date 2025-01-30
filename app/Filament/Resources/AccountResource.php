<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AccountResource\Pages;
use App\Models\Account;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Tables;

class AccountResource extends Resource
{
    protected static ?string $model = Account::class;
    protected static ?string $navigationGroup = 'Master Data';
    protected static ?string $navigationLabel = 'Akun';
    protected static ?string $navigationIcon = 'heroicon-o-folder-open';

    public static function form(Forms\Form $form): Forms\Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('code')
                    ->required()
                    ->maxLength(20),
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(100),
                Forms\Components\Select::make('type')
                    ->options([
                        'asset' => 'Asset',
                        'liability' => 'Liability',
                        'equity' => 'Equity',
                        'revenue' => 'Revenue',
                        'expense' => 'Expense',
                    ])
                    ->required(),
                Forms\Components\Select::make('category')
                    ->options([
                        'aktiva_lancar' => 'Aktiva Lancar',
                        'aktiva_tidak_lancar' => 'Aktiva Tidak Lancar',
                        'liabilitas_lancar' => 'Liabilitas Lancar',
                        'liabilitas_tidak_lancar' => 'Liabilitas Tidak Lancar',
                        'ekuitas' => 'Ekuitas',
                    ])
                    ->required(),
            ])->columns(3);
    }

    public static function table(Tables\Table $table): Tables\Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('code')
                    ->searchable(),
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('type')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'asset' => 'success',
                        'liability' => 'info',
                        'equity' => 'warning',
                        'revenue' => 'gray',
                        'expense' => 'danger',
                    }),
                Tables\Columns\TextColumn::make('category')
                    ->label('Kategori')
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable(),
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
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAccounts::route('/'),
            'create' => Pages\CreateAccount::route('/create'),
            'edit' => Pages\EditAccount::route('/{record}/edit'),
        ];
    }
}
