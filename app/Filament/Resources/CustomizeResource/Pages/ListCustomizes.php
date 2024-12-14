<?php

namespace App\Filament\Resources\CustomizeResource\Pages;

use App\Filament\Resources\CustomizeResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListCustomizes extends ListRecords
{
    protected static string $resource = CustomizeResource::class;

    // protected static string $view = 'filament.resources.users.pages.view-user';


    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
