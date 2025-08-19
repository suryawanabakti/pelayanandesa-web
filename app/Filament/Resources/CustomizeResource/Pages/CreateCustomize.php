<?php

namespace App\Filament\Resources\CustomizeResource\Pages;

use App\Filament\Resources\CustomizeResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;

class CreateCustomize extends CreateRecord
{
    protected static string $resource = CustomizeResource::class;

    protected function handleRecordCreation(array $data): Model
    {
        $data['nik'] = $data['email'];
        return static::getModel()::create($data);
    }
}
