<?php

namespace App\Filament\Resources\CustomizeResource\Pages;

use App\Filament\Resources\CustomizeResource;
use App\Imports\UsersImport;
use Filament\Actions;
use Filament\Forms\Components\FileUpload;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

class ListCustomizes extends ListRecords
{
    protected static string $resource = CustomizeResource::class;

    // protected static string $view = 'filament.resources.users.pages.view-user';


    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
            Actions\Action::make('Export Users')->url('/users/export')->color('warning'),
            Actions\Action::make('Import Users')
                ->label('Import Users')
                ->icon('heroicon-m-arrow-up-tray')
                ->form([
                    FileUpload::make('file')
                        ->helperText(new \Illuminate\Support\HtmlString(
                            'Sila muat naik fail Excel dengan format yang betul. <a href="' . route('users.template') . '" class="text-primary underline">Muat turun templat di sini</a>.'
                        ))
                        ->label('Excel File')
                        ->required()
                        ->disk('public')
                        ->directory('imports')
                        ->acceptedFileTypes(['application/vnd.ms-excel', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', '.xls', '.xlsx'])
                ])
                ->action(function (array $data): void {
                    $path = Storage::disk('public')->path($data['file']);
                    Excel::import(new UsersImport, $path);
                    // Optional: Hapus file selepas import
                    Storage::disk('public')->delete($data['file']);
                })
                ->successNotificationTitle('Import Berjaya!')
                ->color('success')
        ];
    }
}
