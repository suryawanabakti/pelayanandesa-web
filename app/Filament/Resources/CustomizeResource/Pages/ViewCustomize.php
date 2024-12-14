<?php

namespace App\Filament\Resources\CustomizeResource\Pages;

use App\Filament\Resources\CustomizeResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewCustomize extends ViewRecord
{
    protected static string $resource = CustomizeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
