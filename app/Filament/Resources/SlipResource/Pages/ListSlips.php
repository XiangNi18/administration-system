<?php

namespace App\Filament\Resources\SlipResource\Pages;

use App\Filament\Resources\SlipResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSlips extends ListRecords
{
    protected static string $resource = SlipResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
