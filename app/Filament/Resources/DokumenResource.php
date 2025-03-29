<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DokumenResource\Pages;
use App\Filament\Resources\DokumenResource\RelationManagers;
use App\Models\Dokumen;
use Filament\Forms;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class DokumenResource extends Resource
{
    protected static ?string $model = Dokumen::class;


    protected static ?string $navigationIcon = 'heroicon-o-document';
    protected static ?string $navigationGroup = 'Main Menu';
    protected static ?string $navigationLabel = 'Dokumen';

    public static function getGloballySearchableAttributes(): array
    {
        return ['nama'];
    }

    public static function getPluralModelLabel(): string
    {
        return 'Dokumen'; // Customize the plural label
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                FileUpload::make('file')->columnSpan(2)->required()->directory('dokumen'),
                Select::make('masyarakat_id')->options(
                    function () {
                        return \App\Models\User::where('role', 'masyarakat')->pluck('name', 'id');
                    }
                )->label('Masyarakat')->searchable()->preload(),
                TextInput::make('nama')->required()
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('nama')->searchable(),
                TextColumn::make('file')
                    ->label('File')
                    ->formatStateUsing(function ($state) {
                        return $state ? 'Download' : 'No File';
                    })
                    ->url(function ($record) {
                        return $record->file ? url('storage/' . $record->file) : null;
                    })
                    ->openUrlInNewTab() // Optional: Opens the download link in a new tab
                    ->tooltip('Click to download the file')
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListDokumens::route('/'),
            'create' => Pages\CreateDokumen::route('/create'),
            'edit' => Pages\EditDokumen::route('/{record}/edit'),
        ];
    }
}
