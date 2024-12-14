<?php

namespace App\Filament\Resources\CustomizeResource\Pages;

use App\Filament\Resources\CustomizeResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCustomize extends EditRecord
{
    protected static string $resource = CustomizeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
