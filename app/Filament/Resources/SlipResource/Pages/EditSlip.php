<?php

namespace App\Filament\Resources\SlipResource\Pages;

use App\Filament\Resources\SlipResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSlip extends EditRecord
{
    protected static string $resource = SlipResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
